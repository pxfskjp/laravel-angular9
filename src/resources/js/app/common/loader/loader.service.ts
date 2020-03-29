import { Injectable } from '@angular/core';
import { 
    Observable, 
    BehaviorSubject 
} from 'rxjs';

@Injectable({
    providedIn : 'root'
})
export class LoaderService 
{
    private status: BehaviorSubject<boolean>;

    constructor() {
        this.status = new BehaviorSubject<boolean>(false);
    }
    
    public getStatus(): Observable<boolean> {
        return this.status.asObservable();
    }
    
    public visible(): void {
        setTimeout(() => {
            this.status.next(true);
        });
    }
    
    public invisible(): void {
        setTimeout(() => {
            this.status.next(false);
        });
    }
}
