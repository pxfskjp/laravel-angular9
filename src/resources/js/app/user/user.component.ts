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
import { UserService } from './user.service';
import { User } from './user.model';

@Component({
    selector: APP_SELECTORS.USER,
    templateUrl: './templates/user.list.html'
})
export class UserComponent implements AfterViewInit
{
    public updateIcon = faEdit;
    public deleteIcon = faTrash;
    private isSubmitted: BehaviorSubject<any>;
    private dialogRef: MatDialogRef<UserDialog>;
    public displayedColumns: string[] = [
        'action',
        'firstname',
        'lastname',
        'email',
    ];
    public userList = new MatTableDataSource<User>();

    @ViewChild(MatSort, {static: false}) sort: MatSort;

    constructor(
        private dialogService: DialogService,
        private userService: UserService,
        private sortingService: SortingService,
        private updateDialog: MatDialog
    ) { }
    
    ngAfterViewInit(): void {
        this.userService
            .get()
            .subscribe((result: User[]) => {
                this.userList.data = result;
                this.userList.sort = this.sort;
                this.userList.sortData = this.sortingService.sort;
            });
    }
    
    delete(user: User) 
    {
        this.dialogService
            .confirm('Potwierdź usunięcie')
            .subscribe((confirm: boolean) => {
                if (confirm) {
                    this.userService
                        .destroy(user)
                        .subscribe(() => {
                            let index = this.userList.data.findIndex((item: User) => {return item.id == user.id});
                            this.userList.data.splice(index, 1);
                            this.userList.data = [...this.userList.data];
                    });
            }
        });     
    }

    edit(user: User) {
        this.isSubmitted = new BehaviorSubject<any>({});
        this.isSubmitted
            .asObservable()
            .subscribe((value: any) => {
                if (value instanceof User) {
                    this.update(value);
                }
            });
        this.openDialog(user);
    }
    
    update(user: User) {
        this.userService
            .update(user)
            .subscribe(() => {
                this.closeDialog();
                let index = this.userList.data.findIndex((item: User) => { return item.id == user.id });
                this.userList.data[index] = user;
                this.userList.data = [...this.userList.data];
            });
    }

    create() {
        this.isSubmitted = new BehaviorSubject<any>({});
        this.isSubmitted
            .asObservable()
            .subscribe((value: any) => {
                if (value instanceof User) {
                    this.store(value);
                }
            });
        this.openDialog(new User());
    }
    
    store(user: User) {
        this.userService
            .store(user)
            .subscribe((value: User) => {
                this.closeDialog();
                this.userList.data = [...this.userList.data, new User(value)];
            });
    }
    
    openDialog(user: User) {
       this.dialogRef = this.updateDialog.open(UserDialog, {
            data: {
                model: user,
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
        this.userList.filter = filterValue.trim().toLowerCase();
  }
}

@Component({
    templateUrl: './templates/user.update.html'
})
export class UserDialog 
{
    public userForm: FormGroup;
    private isSubmitted: BehaviorSubject<any>;

    constructor(
        private formBuilder: FormBuilder,
        private dialogRef : MatDialogRef<UserDialog>,
        @Inject(MAT_DIALOG_DATA) public data: any
    ) {
        this.userForm = this.formBuilder.group({
            'id': [
                this.data.model.id,
                Validators.required
                ],
            'firstname': [
                this.data.model.firstname,
                Validators.required
                ],
            'lastname': [
                this.data.model.lastname,
                Validators.required
                ],
            'email': [
                this.data.model.email,
                Validators.required
                ],
            'password': [
                this.data.model.password,
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
        return !this.userForm.get(field).valid && this.userForm.get(field).touched;
    }
    
    onSubmit(value: any) 
    {
        let user = new User(value);
        this.isSubmitted.next(user);
    }
}

