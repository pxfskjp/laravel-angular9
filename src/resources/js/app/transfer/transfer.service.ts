import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { finalize, map } from 'rxjs/internal/operators';
import { LoaderService } from '@app/common/loader/loader.service';
import { APP_ROUTES } from '@app/common/appsettings/appsettings';
import { Transfer } from './transfer.model';

@Injectable({
  providedIn: 'root',
})
export class TransferService
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
            .get<any>(APP_ROUTES.TRANSFER)
            .pipe(map(response => {
                    return response.result.map((data:any) => (new Transfer(data)));
                })
            )
            .pipe(
                finalize(() => {
                    this.loaderService
                        .invisible();
                })
            );
            
        
    }
    
    destroy(transfer: Transfer): any 
    {
        this.loaderService
            .visible();
        return this.http
            .delete(APP_ROUTES.TRANSFER + '/' + transfer.id)
            .pipe(
                finalize(() => {
                    this.loaderService
                        .invisible();
                })
            );
    }   
    
    update(transfer: Transfer): any 
    {
        this.loaderService
            .visible();
        return this.http
            .put(`${APP_ROUTES.TRANSFER}/${transfer.id}`, transfer.getArray())
            .pipe(
                finalize(() => {
                    this.loaderService
                        .invisible();
                })
            );
    } 
    
    store(transfer: Transfer): any 
    {
        this.loaderService
            .visible();
        return this.http
            .post<any>(APP_ROUTES.TRANSFER, transfer.getArray())
            .pipe(map(response => {
                    return new Transfer(response.result);
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
