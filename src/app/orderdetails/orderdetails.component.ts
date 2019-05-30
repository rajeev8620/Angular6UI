import { Component, OnInit,ViewChild } from '@angular/core';
import { Router } from '@angular/router';
import { FormBuilder } from '@angular/forms';
import { LoginValidService } from '../services/loginvalid.service';
import { OrderDetailsService } from '../services/order.details.service';
import {MatPaginator, MatSort, MatTableDataSource} from '@angular/material';
import { ConfigService } from '../services/config.service';
import { HttpClient } from '@angular/common/http';
import { ReportDownloadService } from '../services/report.download';
import {saveAs} from 'file-saver';
import { first } from 'rxjs/operators';
import { AuthenticationService } from '../services/authentication.service';
import { ToastrService } from 'ngx-toastr';

@Component({
  selector: 'app-orderdetails',
  templateUrl: './orderdetails.component.html',
  styleUrls: ['./orderdetails.component.css']
})
export class OrderdetailsComponent implements OnInit {
  
  private orderObj:any;
  private userId:any;
  loading = false;
  private orderData:any;
  private useEmail:any;
  private items:any;
  totalDatacount:any;

  private asyncResult:any;

  displayedColumns = ['id', 'FileName', 'UploadedOn','Status','Report'];

  dataSource: MatTableDataSource<UserData>;

  @ViewChild(MatPaginator) paginator: MatPaginator;
  @ViewChild(MatSort) sort: MatSort;
  
  // constructor(private formBuilder: FormBuilder,
  //             private router: Router,
  //             private isValidLog:LoginValidService,
  //             private order:OrderDetailsService,private ConfigService: ConfigService,
  //             private http: HttpClient,
  //             private download:ReportDownloadService) {
  //               var isLoogedIn=this.isValidLog.isValidLogin();
  //               this.useEmail=localStorage.getItem('userEmail');
  //               this.userId=localStorage.getItem('userId');
  //               alert("inside constructor==>"+this.userId);
  //               if(typeof this.useEmail==="undefined" || this.useEmail=='' || this.useEmail==null || isLoogedIn==0){
  //                 this.router.navigateByUrl('/login');
  //               }    
  //  }

      constructor(
        private formBuilder: FormBuilder,
        private router: Router,
        private isValidLog:LoginValidService,
        private order:OrderDetailsService,
        private ConfigService: ConfigService,
        private authenticationService: AuthenticationService,
        private http: HttpClient,
        private download:ReportDownloadService,
        private toastr: ToastrService
    ) {
        this.authenticationService.currentUser.subscribe(
          (dataVal)=>{
            if(dataVal==null){
              this.userId = '';
              this.router.navigateByUrl('/login');
            }else{
              this.userId=localStorage.getItem('userId');
            }
          })
    }

  async ngOnInit() {
    this.loading=true;
    var consumerData:any=await this.getAllOrderData();
    this.totalDatacount=consumerData.length;
    this.dataSource = new MatTableDataSource(consumerData);
    this.dataSource.paginator = this.paginator;
    this.dataSource.sort = this.sort;
    
  }
  

  getAllOrderData() {
    this.orderObj={
      'function':'orderDetails',
      'userId':this.userId
    };

    //alert("inside function==>"+JSON.stringify(this.orderObj));
    return  new Promise((resolve, reject) => {
      this.order.getUserData(this.orderObj)
        .toPromise()
        .then(
          (res:any) => { // Success            
            //this.dataSource = new MatTableDataSource(res.Data[0]);
            this.loading=false;
            resolve(res.Data[0]);
          }
        );
    });
  }
  applyFilter(filterValue: string) {
    this.dataSource.filter = filterValue.trim().toLowerCase();
  }
  downloadReport(reportId,filename){
    this.download.downloadFile(reportId)
    .subscribe(
      (data:any)=>{
        saveAs(data, filename);
      },(error)=>{
        console.log("The error is ",error);

      }
    );
}
  uploadFile(){
    this.router.navigateByUrl('/uploadfile');
  }

}

export interface UserData {
  OrderId: string;
  ConsumerId: string;
  UploadedFileName: string;
  UploadedOn: string;
  Status:string,
  CompletedOn:string,
  reportStatus:string,
  LastModified:string,
}