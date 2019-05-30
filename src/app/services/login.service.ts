import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ConfigService } from './config.service';

@Injectable({ providedIn: 'root' })

export class LoginService {
    constructor(private http: HttpClient,private ConfigService: ConfigService) { }
    
    checkLogin(loginData:any) {
        return this.http.post(this.ConfigService.apiUrl + 'userRegistration.php',loginData);
    }
}