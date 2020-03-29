import { 
    Component, 
    Inject 
} from '@angular/core';
import { 
    MatDialogRef, 
    MAT_DIALOG_DATA 
} from '@angular/material/dialog';

@Component({
    templateUrl: './templates/confirmdialog.html'
})
export class ConfirmDialog
{
   
    constructor(
        public dialogRef : MatDialogRef<ConfirmDialog>,
        @Inject(MAT_DIALOG_DATA) public message: any) {};
    
    cancel() {
        this.dialogRef.close(false);
    };
    
    confirm() {
        this.dialogRef.close(true);
    }
}

