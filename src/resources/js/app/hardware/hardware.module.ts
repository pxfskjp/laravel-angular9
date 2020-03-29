import { NgModule } from '@angular/core';
import {
    MAT_DATE_LOCALE,
    MAT_DATE_FORMATS
} from '@angular/material/core';
import { SharedModule } from '@app/common/shared.module';
import { APP_DATE_FORMATS } from '@app/common/appsettings/appsettings';
import { 
    HardwareComponent, 
    HardwareDialog,
    HardwareLeaseDialog,
    HardwareBackDialog
} from './hardware.component';

@NgModule({
    imports: [ 
        SharedModule
        ],
    declarations: [
        HardwareDialog,
        HardwareComponent,
        HardwareLeaseDialog,
        HardwareBackDialog
        ],
    exports: [ HardwareComponent ],
    providers: [
        { provide: MAT_DATE_LOCALE, useValue: 'pl' },
        { provide: MAT_DATE_FORMATS, useValue: APP_DATE_FORMATS }
        ],
    entryComponents: [
        HardwareDialog,
        HardwareComponent,
        HardwareLeaseDialog,
        HardwareBackDialog
        ]
})

export class HardwareModule {}

