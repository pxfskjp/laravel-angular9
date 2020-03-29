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
import { APP_SELECTORS } from '@app/common/appsettings/appsettings';
import { SortingService } from '@app/common/sorting/sorting.service';
import { SystemService } from './system.service';
import { System } from './system.model';

@Component({
    selector: APP_SELECTORS.SYSTEM,
    templateUrl: './templates/system.list.html'
})
export class SystemComponent implements AfterViewInit
{
    public updateIcon = faEdit;
    public deleteIcon = faTrash;
    private isSubmitted: BehaviorSubject<any>;
    private dialogRef: MatDialogRef<SystemDialog>;
    public displayedColumns: string[] = [
        'action',
        'name',
    ];
    public systemList = new MatTableDataSource<System>();

    @ViewChild(MatSort, {static: false}) sort: MatSort;

    constructor(
        private dialogService: DialogService,
        private systemService: SystemService,
        private sortingService: SortingService,
        private updateDialog: MatDialog
    ) { }
    
    ngAfterViewInit(): void {
        this.systemService
            .get()
            .subscribe((result: System[]) => {
                this.systemList.data = result;
                this.systemList.sort = this.sort;
                this.systemList.sortData = this.sortingService.sort;
            });
    }
    
    delete(system: System) 
    {
        this.dialogService
            .confirm('Potwierdź usunięcie')
            .subscribe((confirm: boolean) => {
                if (confirm) {
                    this.systemService
                        .destroy(system)
                        .subscribe(() => {
                            let index = this.systemList.data.findIndex((item: System) => {return item.id == system.id});
                            this.systemList.data.splice(index, 1);
                            this.systemList.data = [...this.systemList.data];
                    });
            }
        });     
    }

    edit(system: System) {
        this.isSubmitted = new BehaviorSubject<any>({});
        this.isSubmitted
            .asObservable()
            .subscribe((value: any) => {
                if (value instanceof System) {
                    this.update(value);
                }
            });
        this.openDialog(system);
    }
    
    update(system: System) {
        this.systemService
            .update(system)
            .subscribe(() => {
                this.closeDialog();
                let index = this.systemList.data.findIndex((item: System) => { return item.id == system.id });
                this.systemList.data[index] = system;
                this.systemList.data = [...this.systemList.data];
            });
    }

    create() {
        this.isSubmitted = new BehaviorSubject<any>({});
        this.isSubmitted
            .asObservable()
            .subscribe((value: any) => {
                if (value instanceof System) {
                    this.store(value);
                }
            });
        this.openDialog(new System());
    }
    
    store(system: System) {
        this.systemService
            .store(system)
            .subscribe((value: System) => {
                this.closeDialog();
                this.systemList.data = [...this.systemList.data, new System(value)];
            });
    }
    
    openDialog(system: System) {
       this.dialogRef = this.updateDialog.open(SystemDialog, {
            data: {
                model: system,
                isSubmitted: this.isSubmitted
            },
            panelClass: 'modalbox'
        });
    }
    
    closeDialog() {
        this.dialogRef.close();
    }

    applyFilter(event: Event) {
        const filterValue = (event.target as HTMLInputElement).value;
        this.systemList.filter = filterValue.trim().toLowerCase();
    }
}

@Component({
    templateUrl: './templates/system.update.html'
})
export class SystemDialog 
{
    public systemForm: FormGroup;
    private isSubmitted: BehaviorSubject<any>;

    constructor(
        private formBuilder: FormBuilder,
        private dialogRef : MatDialogRef<SystemDialog>,
        @Inject(MAT_DIALOG_DATA) public data: any
    ) {
        this.systemForm = this.formBuilder.group({
            'id': [
                this.data.model.id,
                Validators.required
                ],
            'name': [
                this.data.model.name,
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
        return !this.systemForm.get(field).valid && this.systemForm.get(field).touched;
    }
    
    onSubmit(value: any) 
    {
        let system = new System(value);
        this.isSubmitted.next(system);
    }
}

