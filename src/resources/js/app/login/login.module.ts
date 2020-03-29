import { NgModule } from '@angular/core';
import { 
    FormsModule,
    ReactiveFormsModule
} from '@angular/forms';
import { MatInputModule } from '@angular/material/input';
import { MatButtonModule } from '@angular/material/button';
import { MatDialogModule } from '@angular/material/dialog';
import { LoginComponent, LoginDialog } from './login.component';
import { CommonModule } from "@angular/common";

@NgModule({
    imports: [ 
       CommonModule,
       FormsModule, 
       ReactiveFormsModule,
       MatInputModule,
       MatButtonModule,
       MatDialogModule
       ],
    declarations: [
        LoginComponent,
        LoginDialog
        ],
    entryComponents: [
        LoginComponent,
        LoginDialog
        ]
})
export class LoginModule {}
