import { 
    Component, 
    Inject
} from '@angular/core';
import { 
    MatDialogRef, 
    MAT_DIALOG_DATA 
} from '@angular/material/dialog';

@Component({
    templateUrl: './templates/errordialog.html'
})
export class ErrorDialog
{
    public text: string;
    
    constructor(
        public dialogRef : MatDialogRef<ErrorDialog>,
        @Inject(MAT_DIALOG_DATA) public message: string) {};
    
    cancel() {
        this.dialogRef.close();
    };
}
