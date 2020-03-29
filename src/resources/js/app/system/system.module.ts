import { NgModule } from '@angular/core';
import {
    MAT_DATE_LOCALE,
    MAT_DATE_FORMATS
} from '@angular/material/core';
import { SharedModule } from '@app/common/shared.module';
import { APP_DATE_FORMATS } from '@app/common/appsettings/appsettings';
import { 
    SystemComponent, 
    SystemDialog
} from './system.component';

@NgModule({
    imports: [ 
        SharedModule
        ],
    declarations: [
        SystemDialog,
        SystemComponent,
        ],
    exports: [ SystemComponent ],
    providers: [
        { provide: MAT_DATE_LOCALE, useValue: 'pl' },
        { provide: MAT_DATE_FORMATS, useValue: APP_DATE_FORMATS }
        ],
    entryComponents: [
        SystemDialog,
        SystemComponent
        ]
})

export class SystemModule {}

