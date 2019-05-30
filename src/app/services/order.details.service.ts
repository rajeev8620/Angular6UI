import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ConfigService } from './config.service';

@Injectable({ providedIn: 'root' })
export class OrderDetailsService {
    constructor(private http: HttpClient,private ConfigService: ConfigService) { }
    
    getUserData(orderObj:any){
        return this.http.post(this.ConfigService.apiUrl + 'orderDetails.php',orderObj);
    }
    
}