import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { LoginService } from '../services/login.service';
import { AuthenticationService } from '../services/authentication.service';
import { first } from 'rxjs/operators';
import {AlertService} from '../services/alert.service';
import { ToastrService } from 'ngx-toastr';


@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {
  loginForm: FormGroup;
  loading = false;
  submitted = false;
  returnUrl: string;
  private loginObj:any;
  showAlert=0;
  loginErrorMessage:string;

    constructor(
      private formBuilder: FormBuilder,
      private route: ActivatedRoute,
      private router: Router,
      private LogSer:LoginService, 
      private authenticationService: AuthenticationService,
      private alertService: AlertService,
      private toastr: ToastrService
  ) {
      // redirect to home if already logged in
      if (this.authenticationService.currentUserValue) { 
          this.router.navigate(['/orderdetails']);
      }
  }

  ngOnInit() {
      this.loginForm = this.formBuilder.group({
        email: ['', [Validators.required, Validators.email]],
        password: ['', Validators.required]
    });
  }
  get f() { return this.loginForm.controls; }
  onSubmit() {
    this.submitted = true;

    // stop here if form is invalid
    if (this.loginForm.invalid) {
        return;
    }    
    this.loading = true;
    var emailId=this.loginForm.value.email;
    var password=this.loginForm.value.password;
    this.loginObj={
      'function':'userLogin',
      'Email':emailId,
      'Password':password
    };

    this.authenticationService.login(this.loginObj)
            .pipe(first())
            .subscribe(
                data => {
                  var status=data.Status;
                  this.loginErrorMessage=data.Message;
                  if(status==1){
                    this.router.navigateByUrl('/orderdetails');
                  }else{
                    this.loading = false;
                    this.showAlert=1;
                    setTimeout( () => {
                      this.showAlert = 0;
                    }, 3000);
                  }
                },
                error => {
                  console.log("The error is ",error);
                  this.loading = false;
                });

    // this.LogSer.checkLogin(this.loginObj).subscribe(
    //   (res: any)=>{
    //     this.loading = false;
    //     var status=res.Status;
    //     var Message=res.Message;
    //     if(status==1){
    //       let userArr = Message+"";
    //       let userData = userArr.split("###");
    //       var userName=userData[1]+' '+userData[2];
    //       localStorage.setItem('userEmail', userData[3]);
    //       localStorage.setItem('userId', userData[0]);
    //       localStorage.setItem('userName', userName);
    //       localStorage.setItem('userId', userData[4]);
    //       this.router.navigateByUrl('/orderdetails');
    //     }else{
    //       //already registred email
    //       alert(Message);
    //     }
    //   },(err)=>{
    //     console.log("The error is ",err);
    //   })
}

}
