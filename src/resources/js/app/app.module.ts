import { 
    LOCALE_ID, 
    NgModule,
    ApplicationRef
} from '@angular/core';
import { registerLocaleData } from '@angular/common';
import localePl from '@angular/common/locales/pl';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { 
    HttpClientModule, 
    HTTP_INTERCEPTORS 
} from '@angular/common/http';
import { MatTabsModule } from '@angular/material/tabs';
import { AppComponent } from './app.component';
import { LoaderComponent } from '@app/common/loader/loader.component';
import { AppRoutingModule } from './app-routing.module';
import { LoginModule } from '@app/login/login.module';
import { JwtInterceptor } from '@app/jwt/jwt.interceptor';
import { SharedModule } from '@app/common/shared.module';
import { HardwareModule } from '@app/hardware/hardware.module';
import { SystemModule } from '@app/system/system.module';
import { UserModule } from '@app/user/user.module';
import { TransferModule } from '@app/transfer/transfer.module';

registerLocaleData(localePl);

@NgModule({
    imports: [
        BrowserAnimationsModule,
        HttpClientModule,
        MatTabsModule,
        AppRoutingModule,
        SharedModule,
        LoginModule,
        HardwareModule,
        SystemModule,
        UserModule,
        TransferModule,
        ],
    declarations: [
        LoaderComponent, 
        AppComponent,
        ],
    providers: [ 
        { provide: LOCALE_ID, useValue: 'pl' },
        { provide: HTTP_INTERCEPTORS, useClass: JwtInterceptor, multi: true }
        ]
})

export class AppModule
{
    ngDoBootstrap(app: ApplicationRef) {
        app.bootstrap(AppComponent);
        app.bootstrap(LoaderComponent);
    }
}

