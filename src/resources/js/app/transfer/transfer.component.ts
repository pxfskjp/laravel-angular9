import { 
    Component,
    Inject,
    AfterViewInit,
    ViewChild,
} from '@angular/core';
import {
    MatDialog,
    MatDialogRef,
    MAT_DIALOG_DATA
} from '@angular/material/dialog';
import { MatTableDataSource } from '@angular/material/table';
import { MatSort } from '@angular/material/sort';
import {
    FormBuilder,
    FormGroup,
    Validators
} from '@angular/forms';
import {
    faEdit,
    faTrash
} from '@fortawesome/free-solid-svg-icons';
import { BehaviorSubject } from 'rxjs';
import { DialogService } from '@app/common/dialogs/dialog.service';
import { APP_SELECTORS, APP_TRANSFER_TYPE } from '@app/common/appsettings/appsettings';
import { SortingService } from '@app/common/sorting/sorting.service';
import { TransferService } from './transfer.service';
import { Transfer } from './transfer.model';

@Component({
    selector: APP_SELECTORS.TRANSFER,
    templateUrl: './templates/transfer.list.html'
})
export class TransferComponent implements AfterViewInit
{
    public updateIcon = faEdit;
    public deleteIcon = faTrash;
    private isSubmitted: BehaviorSubject<any>;
    private dialogRef: MatDialogRef<TransferDialog>;
    public displayedColumns: string[] = [
        'hardware_name',
        'type',
        'date',
        'remarks',
    ];
    public transferList = new MatTableDataSource<Transfer>();

    @ViewChild(MatSort, {static: false}) sort: MatSort;

    constructor(
        private dialogService: DialogService,
        private transferService: TransferService,
        private sortingService: SortingService,
        private updateDialog: MatDialog
    ) { }
    
    ngAfterViewInit(): void {
        this.transferService
            .get()
            .subscribe((result: Transfer[]) => {
                this.transferList.data = result;
                this.transferList.sort = this.sort;
                this.transferList.sortData = this.sortingService.sort;
            });
    }
    
    delete(transfer: Transfer) 
    {
        this.dialogService
            .confirm('Potwierdź usunięcie')
            .subscribe((confirm: boolean) => {
                if (confirm) {
                    this.transferService
                        .destroy(transfer)
                        .subscribe(() => {
                            let index = this.transferList.data.findIndex((item: Transfer) => {return item.id === transfer.id});
                            this.transferList.data.splice(index, 1);
                            this.transferList.data = [...this.transferList.data];
                    });
            }
        });     
    }

    edit(transfer: Transfer) {
        this.isSubmitted = new BehaviorSubject<any>({});
        this.isSubmitted
            .asObservable()
            .subscribe((value: any) => {
                if (value instanceof Transfer) {
                    this.update(value);
                }
            });
        this.openDialog(transfer);
    }
    
    update(transfer: Transfer) {
        this.transferService
            .update(transfer)
            .subscribe(() => {
                this.closeDialog();
                let index = this.transferList.data.findIndex((item: Transfer) => { return item.id === transfer.id });
                this.transferList.data[index] = transfer;
                this.transferList.data = [...this.transferList.data];
            });
    }

    create() {
        this.isSubmitted = new BehaviorSubject<any>({});
        this.isSubmitted
            .asObservable()
            .subscribe((value: any) => {
                if (value instanceof Transfer) {
                    this.store(value);
                }
            });
        this.openDialog(new Transfer());
    }
    
    store(transfer: Transfer) {
        this.transferService
            .store(transfer)
            .subscribe((value: Transfer) => {
                this.closeDialog();
                this.transferList.data = [...this.transferList.data, new Transfer(value)];
            });
    }
    
    openDialog(transfer: Transfer) {
       this.dialogRef = this.updateDialog.open(TransferDialog, {
            data: {
                model: transfer,
                isSubmitted: this.isSubmitted
            },
            panelClass: 'modalbox',
            disableClose: true
        });
    }
    
    closeDialog() {
        this.dialogRef.close();
    }
    
    applyFilter(event: Event) {
        const filterValue = (event.target as HTMLInputElement).value;
        this.transferList.filter = filterValue.trim().toLowerCase();
    }
    
    getTransferTypeName(transfer: Transfer) {
        return (transfer.type === APP_TRANSFER_TYPE.LEASE) ? 'Transfer to user' : 'Return to owner';
    }
}

@Component({
    templateUrl: './templates/transfer.update.html'
})
export class TransferDialog 
{
    public transferForm: FormGroup;
    private isSubmitted: BehaviorSubject<any>;

    constructor(
        private formBuilder: FormBuilder,
        private dialogRef : MatDialogRef<TransferDialog>,
        @Inject(MAT_DIALOG_DATA) public data: any
    ) {
        this.transferForm = this.formBuilder.group({
            'id': [
                this.data.model.id,
                Validators.required
                ],
            'user_id': [
                this.data.model.user_id,
                Validators.required
                ],
            'hardware_id': [
                this.data.model.hardware_id,
                Validators.required
                ],
            'type': [
                this.data.model.type,
                Validators.required
                ],
            'date': [
                this.data.model.date,
                Validators.required
                ],
            'remarks': [
                this.data.model.remarks,
                Validators.required
                ],
        });
        this.isSubmitted = this.data.isSubmitted;
    };
    
    cancel() 
    {
        this.dialogRef.close();
    };
    
    noValidate(field: string): boolean 
    {
        return !this.transferForm.get(field).valid && this.transferForm.get(field).touched;
    }
    
    onSubmit(value: any) 
    {
        let transfer = new Transfer(value);
        this.isSubmitted.next(transfer);
    }
}

