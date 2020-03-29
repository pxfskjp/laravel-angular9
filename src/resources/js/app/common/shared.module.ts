import { NgModule } from '@angular/core';
import { MatTableModule } from '@angular/material/table';
import { MatInputModule } from '@angular/material/input';
import { MatButtonModule } from '@angular/material/button';
import { MatDialogModule } from '@angular/material/dialog';
import { MatDatepickerModule } from '@angular/material/datepicker';
import { MatMomentDateModule } from '@angular/material-moment-adapter';
import { MatProgressSpinnerModule } from '@angular/material/progress-spinner';
import { MatSortModule } from '@angular/material/sort';
import { MatSelectModule } from '@angular/material/select';
import { MatAutocompleteModule } from '@angular/material/autocomplete';
import { CommonModule } from '@angular/common';
import { 
    FormsModule, 
    ReactiveFormsModule 
} from '@angular/forms';
import { BrowserModule } from '@angular/platform-browser';
import { FontAwesomeModule } from '@fortawesome/angular-fontawesome';
import { FlexLayoutModule } from '@angular/flex-layout';
import { ErrorDialogModule } from '@app/common/dialogs/errordialog.module';
import { ConfirmDialogModule } from '@app/common/dialogs/confirmdialog.module';
import { A11yModule } from '@angular/cdk/a11y';
import { BidiModule } from '@angular/cdk/bidi';
import { ObserversModule } from '@angular/cdk/observers';
import { OverlayModule } from '@angular/cdk/overlay';
import { PlatformModule } from '@angular/cdk/platform';
import { PortalModule } from '@angular/cdk/portal';
import { CdkStepperModule } from '@angular/cdk/stepper';
import { CdkTableModule } from '@angular/cdk/table';
import { CdkTreeModule } from '@angular/cdk/tree';

@NgModule({
    imports: [ 
        CommonModule,
        MatTableModule,
        MatSortModule,
        MatInputModule,
        MatButtonModule,
        MatDialogModule,
        MatDatepickerModule,
        MatMomentDateModule,
        MatProgressSpinnerModule,
        MatSelectModule,
        MatAutocompleteModule,
        FormsModule,
        ReactiveFormsModule,
        BrowserModule,
        FontAwesomeModule, 
        FlexLayoutModule,
        ErrorDialogModule,
        ConfirmDialogModule,
        A11yModule,
        BidiModule,
        ObserversModule,
        OverlayModule,
        PlatformModule,
        PortalModule,
        CdkStepperModule,
        CdkTableModule,
        CdkTreeModule
        ],
    exports: [  
        CommonModule,
        MatTableModule,
        MatSortModule,
        MatInputModule,
        MatButtonModule,
        MatDialogModule,
        MatDatepickerModule,
        MatMomentDateModule,
        MatProgressSpinnerModule,
        MatSelectModule,
        MatAutocompleteModule,        
        FormsModule,
        ReactiveFormsModule,
        BrowserModule,
        FontAwesomeModule, 
        FlexLayoutModule,
        ErrorDialogModule,
        ConfirmDialogModule,
        A11yModule,
        BidiModule,
        ObserversModule,
        OverlayModule,
        PlatformModule,
        PortalModule,
        CdkStepperModule,
        CdkTableModule,
        CdkTreeModule
    ]
})

export class SharedModule {
    static forRoot() {
        return {
            ngModule: SharedModule
        };
    };
}
