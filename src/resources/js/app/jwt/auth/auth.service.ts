import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { of, Observable, BehaviorSubject } from 'rxjs';
import { catchError, mapTo, tap } from 'rxjs/operators';
import { APP_ROUTES } from '@app/common/appsettings/appsettings';
import { TokenModel } from '@app/jwt/token/token.model';

@Injectable({
    providedIn: 'root'
})
export class AuthService {

    private redirectUrl: string = '/';
    private readonly JWT_TOKEN = 'JWT_ACCESS_TOKEN';
    private readonly REFRESH_TOKEN = 'JWT_REFRESH_TOKEN';
    private readonly USER_EMAIL = 'JWT_USER_EMAIL';
    
    public loggedUser: BehaviorSubject<string>;
    
    constructor(private http: HttpClient) {
        this.loggedUser = new BehaviorSubject<string>(null);
    }

    setRedirectUrl(url: string) {
        this.redirectUrl = url;
    }
    
    getRedirectUrl(): string {
        return this.redirectUrl;
    }
    
    login(user: {email: string, password: string}): Observable<boolean> {
        return this.http.post<any>(APP_ROUTES.LOGIN, user)
            .pipe(
                tap(token => this.doLoginUser(token.result)),
                mapTo(true),
                catchError(() => {
                    return of(false);
                }));
    }

    logout(): Observable<boolean> {
        return this.http
            .post<any>(APP_ROUTES.LOGOUT, {})
            .pipe(
                tap(() => this.doLogoutUser()),
                mapTo(true),
                catchError(error => {
                    alert(error.error);
                    return of(false);
                }
            )
        );
    }

    isLoggedIn() {
        return this.loggedUser.asObservable();
    }

    refreshToken() {
        return this.http.post<any>(APP_ROUTES.REFRESH, {
            'refreshToken': this.getRefreshToken()
        });
    }

    getJwtToken() {
        let token = localStorage.getItem(this.JWT_TOKEN);
        if (null !== token) {
            this.loggedUser.next(localStorage.getItem(this.USER_EMAIL));
        }
        return token;
    }
    
    storeJwtToken(jwt: string) {
        localStorage.setItem(this.JWT_TOKEN, jwt);
    }
    
    storeToken(token: TokenModel) {
        localStorage.setItem(this.JWT_TOKEN, token.accessToken);
        localStorage.setItem(this.REFRESH_TOKEN, token.refreshToken);
        localStorage.setItem(this.USER_EMAIL, token.email);
    }  
    
    removeJwtToken() {
        localStorage.removeItem(this.JWT_TOKEN);
    }  

    private doLoginUser(token: TokenModel) {
        this.loggedUser.next(token.email);
        this.storeToken(token);
    }

    private doLogoutUser() {
        this.loggedUser.next(null);
        this.removeToken();
    }

    private getRefreshToken() {
        return localStorage.getItem(this.REFRESH_TOKEN);
    }

    private removeToken() {
        localStorage.removeItem(this.JWT_TOKEN);
        localStorage.removeItem(this.REFRESH_TOKEN);
        localStorage.removeItem(this.USER_EMAIL);
    }
}