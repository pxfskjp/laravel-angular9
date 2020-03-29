import { NgModule } from '@angular/core';
import { MatDialogModule } from '@angular/material/dialog';
import { MatButtonModule } from '@angular/material/button';
import { ErrorDialog } from './errordialog';

@NgModule({
    imports: [ 
       MatButtonModule,
       MatDialogModule,
       ],
    declarations: [
       ErrorDialog
       ],
    entryComponents: [
       ErrorDialog
       ]
})
export class ErrorDialogModule { }
