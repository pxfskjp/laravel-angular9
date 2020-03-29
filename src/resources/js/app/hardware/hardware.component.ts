import { 
    Component,
    Inject,
    AfterViewInit,
    ViewChild
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
    faTrash,
    faPlaneDeparture,
    faPlaneArrival
} from '@fortawesome/free-solid-svg-icons';
import { BehaviorSubject } from 'rxjs';
import { DialogService } from '@app/common/dialogs/dialog.service';
import { 
    APP_SELECTORS, 
    APP_TRANSFER_TYPE 
} from '@app/common/appsettings/appsettings';
import { Transfer } from '@app/transfer/transfer.model';
import { TransferService } from '@app/transfer/transfer.service';
import { SortingService } from '@app/common/sorting/sorting.service';
import { System } from '@app/system/system.model';
import { User } from '@app/user/user.model';
import { HardwareService } from './hardware.service';
import { Hardware } from './hardware.model';
import moment from 'moment';

@Component({
    selector: APP_SELECTORS.HARDWARE,
    templateUrl: './templates/hardware.list.html'
})
export class HardwareComponent implements AfterViewInit
{
    public updateIcon = faEdit;
    public deleteIcon = faTrash;
    public departureIcon = faPlaneDeparture;
    public arrivalIcon = faPlaneArrival;
    public displayedColumns: string[] = [
        'action',
        'name',
        'system_name',
        'serial_number',
        'production_year',
        'user_fullname'
    ];
    public hardwareList = new MatTableDataSource<Hardware>();
    private systemList: System[];
    public userList: User[];
    private isSubmitted: BehaviorSubject<any>;
    private dialogRef: MatDialogRef<HardwareDialog>;
    public leaseRef: MatDialogRef<HardwareLeaseDialog>;
    public backRef: MatDialogRef<HardwareBackDialog>;

    @ViewChild(MatSort, {static: false}) sort: MatSort;

    constructor(
        private dialogService: DialogService,
        private hardwareService: HardwareService,
        private sortingService: SortingService,
        private transferService: TransferService,
        private updateDialog: MatDialog,
        private leaseDialog: MatDialog,
        private backDialog: MatDialog
    ) { }
    
    ngAfterViewInit(): void {
        this.hardwareService
            .get()
            .subscribe((result: [Hardware[], System[], User[]]) => {
                this.hardwareList.data = result[0];
                this.hardwareList.sort = this.sort;
                this.hardwareList.sortData = this.sortingService.sort;
                this.hardwareList.filterPredicate = (hardware: Hardware, filter: string): boolean => {
                    return (hardware.name.toLowerCase().indexOf(filter) >= 0 ||
                        hardware.serial_number.toLowerCase().indexOf(filter) >= 0 ||
                        ('' + hardware.production_year).indexOf(filter) >= 0 ||
                        this.getSystemName(hardware).toLowerCase().indexOf(filter) >= 0 ||
                        this.filterUserName(hardware, filter));
                };
                this.systemList = result[1];
                this.userList = result[2];
            });
    }
    
    delete(hardware: Hardware) 
    {
        this.dialogService
            .confirm('Confirm delete')
            .subscribe((confirm: boolean) => {
                if (confirm) {
                    this.hardwareService
                        .destroy(hardware)
                        .subscribe(() => {
                            let index = this.hardwareList.data.findIndex((item: Hardware) => {return item.id == hardware.id});
                            this.hardwareList.data.splice(index, 1);
                            this.hardwareList.data = [...this.hardwareList.data];
                    });
            }
        });     
    }

    edit(hardware: Hardware) {
        this.isSubmitted = new BehaviorSubject<any>({});
        this.isSubmitted
            .asObservable()
            .subscribe((value: any) => {
                if (value instanceof Hardware) {
                    this.update(value);
                }
            });
        this.openDialog(hardware);
    }
    
    update(hardware: Hardware) {
        this.hardwareService
            .update(hardware)
            .subscribe(() => {
                this.closeDialog();
                let index = this.hardwareList.data.findIndex((item: Hardware) => { return item.id == hardware.id });
                this.hardwareList.data[index] = hardware;
                this.hardwareList.data = [...this.hardwareList.data];
            });
    }

    create() {
        this.isSubmitted = new BehaviorSubject<any>({});
        this.isSubmitted
            .asObservable()
            .subscribe((value: any) => {
                if (value instanceof Hardware) {
                    this.store(value);
                }
            });
        this.openDialog(new Hardware());
    }
    
    store(hardware: Hardware) {
        this.hardwareService
            .store(hardware)
            .subscribe((hardware: Hardware) => {
                this.closeDialog();
                this.hardwareList.data = [...this.hardwareList.data, hardware];
            });
    }
    
    openDialog(hardware: Hardware) {
        let system = hardware.system_id ? this.systemList.find(system => system.id === hardware.system_id) : null;
        this.dialogRef = this.updateDialog
            .open(HardwareDialog, {
                data: {
                    model: hardware,
                    system: system,
                    systemList: this.systemList,
                    isSubmitted: this.isSubmitted
                },
                panelClass: 'modalbox',
                disableClose: true
            });
    }
    
    lease(hardware: Hardware) {
        this.isSubmitted = new BehaviorSubject<any>({});
        this.isSubmitted
            .asObservable()
            .subscribe((value: any) => {
                 if (value instanceof Transfer) {
                     this.storeLease(value);
                 }
            });
        this.leaseRef = this.leaseDialog
            .open(HardwareLeaseDialog, {
                data: {
                    model: hardware,
                    userList: this.userList.filter((user: User) => user.hardware_id === null),
                    isSubmitted: this.isSubmitted
                },
                panelClass: 'modalbox',
                disableClose: true
            });
    }
    
    storeLease(value: Transfer) {
        this.transferService
            .store(value)
            .subscribe(() => {
                this.closeLeaseDialog();
            });
    }
    
    back(hardware: Hardware) {
        this.isSubmitted = new BehaviorSubject<any>({});
        this.isSubmitted
            .asObservable()
            .subscribe((value: any) => {
                 if (value instanceof Transfer) {
                     this.storeLease(value);
                 }
            });
        this.backRef = this.backDialog
            .open(HardwareBackDialog, {
                data: {
                    model: hardware,
                    userList: this.userList.filter((user: User) => user.hardware_id === null),
                    isSubmitted: this.isSubmitted
                },
                panelClass: 'modalbox',
                disableClose: true
            });
    }    
    
    closeDialog() {
        this.dialogRef.close();
    }
    
    closeLeaseDialog() {
        this.leaseRef.close();
    }
    
    applyFilter(event: Event) {
        const filterValue = (event.target as HTMLInputElement).value;
        this.hardwareList.filter = filterValue.trim().toLowerCase();
    }
    
    filterUserName(hardware: Hardware, filter: string): boolean {
        if (hardware) {
            let user = this.userList.find(user => user.hardware_id === hardware.id);
            return user ? user.firstname.toLowerCase().indexOf(filter) >= 0 || user.lastname.toLowerCase().indexOf(filter) >= 0 : false;
        }
        return false;
    }
    
    getSystemName(hardware: Hardware): string
    {
        if (hardware && hardware.system_id) {
            let system = this.systemList.find(system => system.id === hardware.system_id);
            return system ? system.name : '';
        }
        return '';
    }
    
    getUserFullname(hardware: Hardware): string
    {
        if (hardware) {
            let user = this.userList.find(user => user.id === hardware.user_id);
            return user ? `${user.firstname} ${user.lastname}` : '';
        }
        return '';
    }    
}

@Component({
    templateUrl: './templates/hardware.update.html'
})
export class HardwareDialog 
{
    public hardwareForm: FormGroup;
    private isSubmitted: BehaviorSubject<any>;
    public systemList: System[];

    constructor(
        private formBuilder: FormBuilder,
        private dialogRef : MatDialogRef<HardwareDialog>,
        @Inject(MAT_DIALOG_DATA) public data: any
    ) {
        this.systemList = this.data.systemList;
        this.hardwareForm = this.formBuilder.group({
            'id': [
                this.data.model.id,
                Validators.required
                ],
            'system': [
                this.data.model.system_id ? 
                    this.systemList.find(system => system.id === this.data.model.system_id) :
                    new System({})
                ],
            'name': [
                this.data.model.name,
                Validators.required
                ],
            'serial_number': [
                this.data.model.serial_number,
                Validators.required
                ],
            'production_year': [
                this.data.model.production_year,
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
        return !this.hardwareForm.get(field).valid && this.hardwareForm.get(field).touched;
    }
    
    onSubmit(value: any) 
    {
        let hardware = new Hardware(value);
        hardware.system_id = value.system ? value.system.id : null;
        this.isSubmitted.next(hardware);
    }
    
    systemName(system: System): string 
    {
        return system && system.name ? system.name : '';
    }   
}

@Component({
    templateUrl: './templates/hardware.lease.html'
})
export class HardwareLeaseDialog 
{
    public leaseForm: FormGroup;
    public userList: User[];
    private isSubmitted: BehaviorSubject<any>;
    private hardware: Hardware;

    constructor(
        private formBuilder: FormBuilder,
        private dialogRef : MatDialogRef<HardwareLeaseDialog>,
        @Inject(MAT_DIALOG_DATA) public data: any
    ) {
        this.userList = this.data.userList;
        this.hardware = this.data.model;
        this.leaseForm = this.formBuilder.group({
            'user': [
                new User({}),
                Validators.required
                ],
            'date': [
                moment(),
                Validators.required
                ],
             'remarks': []
        });
        this.isSubmitted = this.data.isSubmitted;
    };
    
    cancel() 
    {
        this.dialogRef.close();
    };
    
    noValidate(field: string): boolean 
    {
        return !this.leaseForm.get(field).valid && this.leaseForm.get(field).touched;
    }
    
    onSubmit(value: any) 
    {
        value.type = APP_TRANSFER_TYPE.LEASE;
        value.user_id = value.user.id;
        value.hardware_id = this.hardware.id;
        let transfer = new Transfer(value);
        this.isSubmitted.next(transfer);
    }
    
    userFullName(user: User): string 
    {
        return user ? `${user.firstname} ${user.lastname}` : '';
    }   
}

@Component({
    templateUrl: './templates/hardware.back.html'
})
export class HardwareBackDialog 
{
    public leaseForm: FormGroup;
    public userList: User[];
    private isSubmitted: BehaviorSubject<any>;
    private hardware: Hardware;

    constructor(
        private formBuilder: FormBuilder,
        private dialogRef : MatDialogRef<HardwareBackDialog>,
        @Inject(MAT_DIALOG_DATA) public data: any
    ) {
        this.userList = this.data.userList;
        this.hardware = this.data.model;
        this.leaseForm = this.formBuilder.group({
            'user': [
                {
                    value: this.userList.find(user => user.id === this.hardware.user_id),
                    disabled: true
                }
             ],
            'date': [
                moment(),
                Validators.required
             ],
             'remarks': []
        });
        this.isSubmitted = this.data.isSubmitted;
    };
    
    cancel() 
    {
        this.dialogRef.close();
    };
    
    noValidate(field: string): boolean 
    {
        return !this.leaseForm.get(field).valid && this.leaseForm.get(field).touched;
    }
    
    onSubmit(value: any) 
    {
        value.type = APP_TRANSFER_TYPE.BACK;
        value.user_id = this.hardware.user_id;
        value.hardware_id = this.hardware.id;
        let transfer = new Transfer(value);
        this.isSubmitted.next(transfer);
    }
    
    userFullName(user: User): string 
    {
        return user ? `${user.firstname} ${user.lastname}` : '';
    }   
}

