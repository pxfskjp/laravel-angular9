import { Injectable } from '@angular/core';
import { MatSort } from '@angular/material/sort';
import moment from 'moment';

@Injectable({
    providedIn : 'root'
})
export class SortingService 
{
    sort(data: any[], sort: MatSort): any[] {
        const active = sort.active;
        const direction = sort.direction;
        if (!active || direction == '') { 
            return data;
        }
        return data.sort((a, b) => {
            let valueA = a[active];
            let valueB = b[active];
            let comparatorResult = 0;
            if (valueA instanceof moment && valueB instanceof moment) {
                comparatorResult = moment(valueA) > moment(valueB) ? 1 : -1;
            } else if (typeof valueA === 'number' && typeof valueB === 'number') {
                comparatorResult = valueA - valueB;
            } else {
                comparatorResult = valueA.localeCompare(valueB);
            }
            return comparatorResult * (direction == 'asc' ? 1 : -1);
        });
    };
}