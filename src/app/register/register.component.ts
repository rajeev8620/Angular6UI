import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { first } from 'rxjs/operators';
import { MustMatch } from '../helpers/must-match.validator';
import { UserService } from '../services/user.service';
import { ToastrManager } from 'ng6-toastr-notifications';
import { AuthenticationService } from '../services/authentication.service';
import {AlertService} from '../services/alert.service';
import { ToastrService } from 'ngx-toastr';


@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css']
})
export class RegisterComponent implements OnInit {
  registerForm: FormGroup;
  loading = false;
  submitted = false;
  regSuccess=0;
  regExist=0;

    constructor(
      private formBuilder: FormBuilder,
      private router: Router,
      private user: UserService,
      private authenticationService: AuthenticationService,
      private userService: UserService,
      private alertService: AlertService,
      private toastr: ToastrService
  ) { 
      // redirect to home if already logged in
      if (this.authenticationService.currentUserValue) {
          this.router.navigateByUrl('/orderdetails');
      }
  }

    private regObj:any;
  ngOnInit() {
      this.registerForm = this.formBuilder.group({
        firstName: ['', Validators.required],
        lastName: ['', Validators.required],
        email: ['', [Validators.required, Validators.email]],
        password: ['', [Validators.required, Validators.minLength(6)]],
        confirmPassword: ['', Validators.required]
    }, {
        validator: MustMatch('password', 'confirmPassword')
    });
  }
  get f() { return this.registerForm.controls; }
  onSubmit() {
    this.submitted = true;

    // stop here if form is invalid
    if (this.registerForm.invalid) {
        return;
    }

    this.loading = true;
    var firstName=this.registerForm.value.firstName;
    var lastName=this.registerForm.value.lastName;
    var emailId=this.registerForm.value.email;
    var password=this.registerForm.value.password;
    this.regObj={
      'function':'userRegistration',
      'FirstName':firstName,
      'LastName':lastName,
      'Email':emailId,
      'Password':password
    };
    this.user.register(this.regObj).subscribe(
      (res: any)=>{
        this.loading = false;
        var status=res.Status;
        var Message=res.Message;
        if(status==1){
          this.regSuccess=1;
          setTimeout( () => {
            this.regSuccess = 0;
          }, 5000);
          this.regExist=0;
          //this.registerForm.reset();
        }else if(status==2){
          //already registred email
          this.regExist=1;
          setTimeout( () => {
            this.regExist = 0;
          }, 3000);
          this.regSuccess=0;
        }
      },(err)=>{
        console.log("The error is ",err);
      }
    )
  }

}
