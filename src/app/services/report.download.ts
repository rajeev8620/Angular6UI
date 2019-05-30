import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { ConfigService } from './config.service';
import { Observable } from 'rxjs';

@Injectable({ providedIn: 'root' })
export class ReportDownloadService {
    constructor(private http: HttpClient,private ConfigService: ConfigService) { }

    downloadFile(orderId:any){
        let uri:any=this.ConfigService.apiUrl + 'downloadReport.php';
        var body = {orderId:orderId};

        return this.http.post(uri,body,{
            responseType : 'blob',
            headers:new HttpHeaders().append('Content-Type','application/json')
        });
    }
    downloadSampleFile(fileType:any){
        let uri:any=this.ConfigService.apiUrl + 'downloadSampleFile.php';
        var body = {fileType:fileType};

        return this.http.post(uri,body,{
            responseType : 'blob',
            headers:new HttpHeaders().append('Content-Type','application/json')
        });
    }
    
    public getPDF(orderId:any): Observable<Blob> { 
        
        let uri=this.ConfigService.apiUrl + 'downloadReport.php';
        let headers = new HttpHeaders();
        headers  = headers.append('responseType', 'blob');
        var body = {orderId:orderId};
        return this.http.post(uri,body,{
            responseType : 'blob',
            headers:new HttpHeaders().append('Content-Type','application/json')
        });
    }
}