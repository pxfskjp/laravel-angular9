import { NgModule } from '@angular/core';
import { MatDialogModule } from '@angular/material/dialog';
import { MatButtonModule } from '@angular/material/button';
import { ConfirmDialog } from "./confirmdialog";

@NgModule({
    imports: [ 
       MatButtonModule, 
       MatDialogModule,
       ],
    declarations: [
       ConfirmDialog
       ],
    entryComponents: [
       ConfirmDialog
       ]
})
export class ConfirmDialogModule { }
