import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { finalize, map } from 'rxjs/internal/operators';
import { LoaderService } from '@app/common/loader/loader.service';
import { APP_ROUTES } from '@app/common/appsettings/appsettings';
import { System } from './system.model';

@Injectable({
  providedIn: 'root',
})
export class SystemService
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
            .get<any>(APP_ROUTES.SYSTEM)
            .pipe(map(response => {
                    return response.result.map((data:any) => (new System(data)));
                })
            )
            .pipe(
                finalize(() => {
                    this.loaderService
                        .invisible();
                })
            );
            
        
    }
    
    destroy(system: System): any 
    {
        this.loaderService
            .visible();
        return this.http
            .delete(APP_ROUTES.SYSTEM + '/' + system.id)
            .pipe(
                finalize(() => {
                    this.loaderService
                        .invisible();
                })
            );
    }   
    
    update(system: System): any 
    {
        this.loaderService
            .visible();
        return this.http
            .put(`${APP_ROUTES.SYSTEM}/${system.id}`, system.getArray())
            .pipe(
                finalize(() => {
                    this.loaderService
                        .invisible();
                })
            );
    } 
    
    store(system: System): any 
    {
        this.loaderService
            .visible();
        return this.http
            .post<any>(APP_ROUTES.SYSTEM, system.getArray())
            .pipe(map(response => {
                    return new System(response.result);
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
