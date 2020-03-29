import { NgModule } from '@angular/core';
import { 
    RouterModule, 
    Routes 
} from '@angular/router';
import { LoginComponent } from '@app/login/login.component.ts';
import { AuthGuard } from '@app/jwt/auth/auth.guard';
import { HardwareComponent } from '@app/hardware/hardware.component.ts';
import { SystemComponent } from '@app/system/system.component.ts';
import { UserComponent } from '@app/user/user.component.ts';
import { TransferComponent } from '@app/transfer/transfer.component';

const routes: Routes = [
    { path: '', redirectTo: '/hardware', pathMatch: 'full' },
    { path: 'login', component: LoginComponent, pathMatch: 'full' },
    { path: 'hardware', component:  HardwareComponent, pathMatch: 'full', canActivate: [AuthGuard] },
    { path: 'system', component:  SystemComponent, pathMatch: 'full', canActivate: [AuthGuard] },
    { path: 'user', component:  UserComponent, pathMatch: 'full', canActivate: [AuthGuard] },
    { path: 'history', component:  TransferComponent, pathMatch: 'full', canActivate: [AuthGuard] },    
];

export const appRouting = RouterModule.forRoot(routes);

@NgModule({
    imports: [
        RouterModule.forRoot(routes),
    ],
    exports: [ RouterModule ]
})

export class AppRoutingModule { }
