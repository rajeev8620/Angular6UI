import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { BehaviorSubject, Observable } from 'rxjs';
import { map } from 'rxjs/operators';

import { User } from '../models/user';
import { ConfigService } from './config.service';

@Injectable({ providedIn: 'root' })
export class AuthenticationService {
    private currentUserSubject: BehaviorSubject<User>;
    public currentUser: Observable<User>;

    constructor(private http: HttpClient,private ConfigService: ConfigService) {
        this.currentUserSubject = new BehaviorSubject<User>(JSON.parse(localStorage.getItem('currentUser')));
        this.currentUser = this.currentUserSubject.asObservable();
    }

    public get currentUserValue(): User {
        return this.currentUserSubject.value;
    }

    login(loginData:any) {
        return this.http.post<any>(this.ConfigService.apiUrl + 'userRegistration.php',loginData)
            .pipe(map(user => {
                // login successful if there's a jwt token in the response
                if (user) {
                    var status=user.Status;
                    var Message=user.Message;
                    if(status==1){
                        let userArr = Message+"";
                        let userData = userArr.split("###");
                        var userName=userData[1]+' '+userData[2];
                        localStorage.setItem('userEmail', userData[3]);
                        localStorage.setItem('userId', userData[0]);
                        localStorage.setItem('userName', userName);
                        localStorage.setItem('userId', userData[0]);
                        localStorage.setItem('currentUser', userData[0]);
                        this.currentUserSubject.next(user);  
                    }                                      
                }
                return user;
            }));
    }

    logout() {
        // remove user from local storage to log user out
        if (localStorage.length > 0) {
            localStorage.clear();      
          }          
        localStorage.removeItem('currentUser');
        this.currentUserSubject.next(null);
    }
}