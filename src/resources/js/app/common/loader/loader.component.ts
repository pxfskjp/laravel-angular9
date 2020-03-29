import { 
    Component, 
    OnInit
} from '@angular/core';
import { LoaderService } from '@app/common/loader/loader.service';

@Component({
    selector: 'app-loader',
    templateUrl: './templates/loader.html',
    styleUrls: [ 'loader.scss' ]
})
export class LoaderComponent implements OnInit
{
    showSpinner: boolean = true;

    constructor(
        private loaderService: LoaderService) {}

    ngOnInit() {
        this.loaderService.getStatus()
            .subscribe(value => {
                this.showSpinner = value;
            });
    }
}