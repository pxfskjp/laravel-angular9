import { Injectable } from '@angular/core';
import { CanActivate, Router, RouterStateSnapshot, ActivatedRouteSnapshot } from '@angular/router';
import { AuthService } from './auth.service';

@Injectable({
    providedIn: 'root'
})
export class AuthGuard implements CanActivate {

    constructor(
        private authService: AuthService,
        private router: Router
    ) { }

    canActivate(_route: ActivatedRouteSnapshot, state: RouterStateSnapshot): boolean {

        if (this.authService.isLoggedIn()) {
            return true;
        }
        this.authService.setRedirectUrl(state.url);
        this.router.navigate(['login']);
        return false;
    }
}