import { Injectable } from '@angular/core';
import { HttpRequest, HttpHandler, HttpEvent, HttpInterceptor, HttpErrorResponse } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError, switchMap } from 'rxjs/operators';
import { AuthService } from '@app/jwt/auth/auth.service';
import { HTTP_ERRORS } from '@app/common/appsettings/appsettings';
import { Router } from '@angular/router';
import { DialogService } from '@app/common/dialogs/dialog.service';

@Injectable()
export class JwtInterceptor implements HttpInterceptor {

    constructor(
        private authService: AuthService,
        private router: Router,
        private dialogService: DialogService) { }

    intercept(request: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
        let jwtToken = this.authService.getJwtToken();
        if (jwtToken !== null) {
            request = this.addToken(request, jwtToken);
        }
        return next.handle(request)
            .pipe(catchError(error => {
                if (error instanceof HttpErrorResponse) {
                    switch (error.status) {
                        case HTTP_ERRORS.UNAUTHORIZED:
                            return this.handleUnauthorizedError(request, next);
                        case HTTP_ERRORS.FORBIDDEN:
                            return this.handleForbiddenError(error);
                        case HTTP_ERRORS.VALIDATION_ERROR:
                            let errorMessage = `${error.error.message}<br/>${error.error.message}`;
                            this.dialogService.error(errorMessage);
                            break;
                        default:
                            this.dialogService.error(`${error.message}`);
                    }
                }
                return throwError(error);
            }));
    }

    private addToken(request: HttpRequest<any>, token: string) {
        return request.clone({
            setHeaders: {
                'Authorization': `Bearer ${token}`
            }
        });
    }

    private handleUnauthorizedError(request: HttpRequest<any>, next: HttpHandler) {
        return this.authService
            .refreshToken()
            .pipe(
                switchMap((response: any) => {
                    this.authService.storeToken(response.result);
                    return next.handle(this.addToken(request, response.result.accessToken));
                }),
                catchError(this.handleForbiddenError)
            );
    }
    
    private handleForbiddenError(error: Response|any) {
        this.router.navigate(['login']);
        return throwError(error);
    }
}