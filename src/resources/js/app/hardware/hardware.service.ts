import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { forkJoin } from "rxjs";
import { finalize, map } from 'rxjs/internal/operators';
import { LoaderService } from '@app/common/loader/loader.service';
import { APP_ROUTES } from '@app/common/appsettings/appsettings';
import { System } from '@app/system/system.model';
import { User } from '@app/user/user.model';
import { Hardware } from './hardware.model';

@Injectable({
  providedIn: 'root',
})
export class HardwareService
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
        return forkJoin(
                this.http.get<any[]>(APP_ROUTES.HARDWARE),
                this.http.get<System[]>(APP_ROUTES.SYSTEM),
                this.http.get<User[]>(APP_ROUTES.USER))
            .pipe(map((response: [any, any, any]) => {
                return [
                    response[0].result.map((hardware: any) => this.mapHardware(hardware)),
                    response[1].result.map((system: System) => new System(system)),
                    response[2].result.map((user: User) => new User(user))
                ]})
            )
            .pipe(
                finalize(() => {
                    this.loaderService
                        .invisible();
                })
            );
            
        
    }
    
    destroy(hardware: Hardware): any 
    {
        this.loaderService
            .visible();
        return this.http
            .delete(APP_ROUTES.HARDWARE + '/' + hardware.id)
            .pipe(
                finalize(() => {
                    this.loaderService
                        .invisible();
                })
            );
    }   
    
    update(hardware: Hardware): any 
    {
        this.loaderService
            .visible();
        return this.http
            .put(`${APP_ROUTES.HARDWARE}/${hardware.id}`, hardware.getArray())
            .pipe(
                finalize(() => {
                    this.loaderService
                        .invisible();
                })
            );
    } 
    
    store(hardware: Hardware): any 
    {
        this.loaderService
            .visible();
        return this.http
            .post<any>(APP_ROUTES.HARDWARE, hardware.getArray())
            .pipe(map(response => {
                    return this.mapHardware(response.result);
                })
            )
            .pipe(
                finalize(() => {
                    this.loaderService
                        .invisible();
                })
            );
    }
    
    mapHardware(hardware: any): Hardware {
        hardware.system_id = hardware.system ? hardware.system.system_id : null;
        hardware.user_id = hardware.user ? hardware.user.user_id : null;
        return new Hardware(hardware); 
    }
}