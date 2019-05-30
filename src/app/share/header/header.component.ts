import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { LoginValidService} from '../../services/loginvalid.service';
import { AuthenticationService } from '../../services/authentication.service';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css']
})
export class HeaderComponent implements OnInit {
  private useEmail:string;
  private userName:string;
  private displayUser:number=0;
  private isLoogedIn:any;

  currentUser: any;
    constructor(
      private router: Router,
      private isValidLog:LoginValidService,
      private authenticationService: AuthenticationService
    ){
        this.authenticationService.currentUser.subscribe(
          (dataVal)=>{
            if(dataVal==null){
              this.currentUser = '';
              this.router.navigateByUrl('/login');
            }else{
              this.currentUser = dataVal;

            }
          },(error)=>{
            console.log("The error is ",error);
          })
    }

  ngOnInit() {
  }

  logOutUser() {
    this.authenticationService.logout();
    this.router.navigate(['/login']);
}

  logOut1(){
    if (localStorage.length > 0) {
      localStorage.clear();
      this.router.navigateByUrl('/login');

    }
  }

}
