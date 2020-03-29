import { Injectable } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { ErrorDialog } from './errordialog';
import { ConfirmDialog } from './confirmdialog';

@Injectable({
    providedIn : 'root'
})
export class DialogService 
{
    
    constructor(
        private dialog: MatDialog) {}
    
    error(message: string) {
        this.dialog
            .open(ErrorDialog, {
                panelClass: 'errorbox',
                data: message
                });
    };
    
    confirm(message: string) {
        return this.dialog
            .open(ConfirmDialog, {
                panelClass: 'confirmbox',
                data: message
                })
            .afterClosed();
    }
}