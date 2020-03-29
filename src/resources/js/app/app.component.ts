import { 
    Component,
    OnInit,
    AfterViewInit
} from '@angular/core';
import { Router } from '@angular/router';
import { faSignOutAlt } from '@fortawesome/free-solid-svg-icons';
import { AuthService } from '@app/jwt/auth/auth.service';

@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
})

export class AppComponent implements OnInit, AfterViewInit
{
    public isLoggedIn: boolean;
    public logoutIcon = faSignOutAlt;

    constructor(
        public router: Router,
        private authService: AuthService)
    {}

    ngOnInit(): void {
            this.isLoggedIn = false;
    }
    
    ngAfterViewInit() {
        this.authService
            .isLoggedIn()
            .subscribe(user => {
                setTimeout(() => {
                    this.isLoggedIn = (null === user) ? false : true;
                });
            })
    }
    
    isActive(url: string): boolean {
        return this.router.url === url;
    }
    
    logout(): void {
        this.authService
            .logout()
            .subscribe(success => {
                if (success) {
                    this.router.navigate(['login']);
                 }
             });
    }
}
