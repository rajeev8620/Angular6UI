import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ConfigService } from './config.service';

@Injectable({ providedIn: 'root' })
export class LoginValidService {
    private loginTrue:any;
    constructor(private http: HttpClient,private ConfigService: ConfigService) { }
    
    isValidLogin() {
        this.loginTrue=0;
        if (localStorage.length > 0) {
            this.loginTrue=1;      
          }
        return this.loginTrue;
    }
}