import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { finalize, map } from 'rxjs/internal/operators';
import { LoaderService } from '@app/common/loader/loader.service';
import { APP_ROUTES } from '@app/common/appsettings/appsettings';
import { User } from './user.model';

@Injectable({
  providedIn: 'root',
})
export class UserService
{
    constructor(
        private http: HttpClient, 
        private loaderService: LoaderService
    ) {
    }
        
    get(): any 
    {
        this.loaderService
            .visible();
        return this.http
            .get<any>(APP_ROUTES.USER)
            .pipe(map(response => {
                    return response.result.map((data:any) => (new User(data)));
                })
            )
            .pipe(
                finalize(() => {
                    this.loaderService
                        .invisible();
                })
            );
            
        
    }
    
    destroy(user: User): any 
    {
        this.loaderService
            .visible();
        return this.http
            .delete(APP_ROUTES.USER + '/' + user.id)
            .pipe(
                finalize(() => {
                    this.loaderService
                        .invisible();
                })
            );
    }   
    
    update(user: User): any 
    {
        this.loaderService
            .visible();
        return this.http
            .put(`${APP_ROUTES.USER}/${user.id}`, user.getArray())
            .pipe(
                finalize(() => {
                    this.loaderService
                        .invisible();
                })
            );
    } 
    
    store(user: User): any 
    {
        this.loaderService
            .visible();
        return this.http
            .post<any>(APP_ROUTES.USER, user.getArray())
            .pipe(map(response => {
                    return new User(response.result);
                })
            )
            .pipe(
                finalize(() => {
                    this.loaderService
                        .invisible();
                })
            );
    }     
}
