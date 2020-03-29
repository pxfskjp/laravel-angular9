import { Component, OnInit } from '@angular/core';
import { 
    MatDialog, 
    MatDialogRef 
} from '@angular/material/dialog';
import { 
    FormGroup, 
    FormBuilder, 
    Validators
} from "@angular/forms";
import { AuthService } from '@app/jwt/auth/auth.service';
import { Router } from '@angular/router';

@Component({
    template: ''
})
export class LoginComponent implements OnInit
{
    constructor(
        private dialog: MatDialog,
        private route: Router,
        private authService: AuthService
    ) { };
    
    ngOnInit(): void {
        this.dialog
            .open(LoginDialog, {
                panelClass: 'modalbox'
                })
            .afterClosed()
            .subscribe(() => {
                this.route.navigate([this.authService.getRedirectUrl()]);
            });
    };
}

@Component({
    templateUrl: './templates/login.dialog.html'
})
export class LoginDialog 
{

    public loginForm: FormGroup;  
   
    constructor(
        private formBuilder: FormBuilder,
        private dialogRef : MatDialogRef<LoginDialog>, 
        private authService: AuthService
        ) {
        
        this.loginForm = this.formBuilder.group({
            'email': ['', [Validators.required, Validators.email]],
            'password': ['', Validators.required]
          });  
    };
    
    cancel() {
        this.dialogRef.close();
    };
    
    onSubmit(value: any) {
        this.authService
            .login({email: value.email, password: value.password})
            .subscribe(success => {
                if (success) {
                    this.cancel();
                 }
             });
    }
}
