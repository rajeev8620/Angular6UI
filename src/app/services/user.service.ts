import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

import { User } from '../models/user';
import { ConfigService } from './config.service';

@Injectable({ providedIn: 'root' })
export class UserService {
    constructor(private http: HttpClient,private ConfigService: ConfigService) { }
    
    register(userData: User) {
        return this.http.post(this.ConfigService.apiUrl + 'userRegistration.php',userData);
    }
}