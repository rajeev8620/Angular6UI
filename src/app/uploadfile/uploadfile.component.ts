import { Component, OnInit } from '@angular/core';
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {FileUploader} from "ng2-file-upload";
import {Observable} from "rxjs";
import {HttpClient} from "@angular/common/http";
import { Router } from '@angular/router';
import { ConfigService } from '../services/config.service';
import { AuthenticationService } from '../services/authentication.service';
import {saveAs} from 'file-saver';
import { ReportDownloadService } from '../services/report.download';

@Component({
  selector: 'app-uploadfile',
  templateUrl: './uploadfile.component.html',
  styleUrls: ['./uploadfile.component.css']
})
export class UploadfileComponent implements OnInit {
  uploadForm: FormGroup;
  private userId:any;
  loading=0;
  uploadSuc=0;
  uploadFail=0;
  noFileSelected=0;
  reportGenerationMessage:string;

  public uploader:FileUploader = new FileUploader({
    isHTML5: true
  });
  constructor(
    private fb: FormBuilder, 
    private http: HttpClient,
    private ConfigService: ConfigService ,
    private router: Router,
    private authenticationService: AuthenticationService,
    private download:ReportDownloadService
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
  
  uploadSubmit(){
    var fileLength=this.uploader.queue.length;
    if(fileLength==0){
      this.noFileSelected=1;
      setTimeout( () => {
        this.noFileSelected = 0;
      }, 2000);
      return false;
    }else if(fileLength>0){
      this.loading=1;
      this.noFileSelected=0;
      var data = new FormData();
      for (let i = 0; i < this.uploader.queue.length; i++) {
        let fileItem = this.uploader.queue[i]._file;
      }
      for (let j = 0; j < this.uploader.queue.length; j++) {
        
        let fileItem = this.uploader.queue[j]._file;
        data.append('file[]', fileItem);
        data.append('fileSeq[]', 'seq'+j);
        data.append('consumerId',this.userId);
        data.append( 'dataType[]', this.uploadForm.controls.type.value);
      }
      this.uploadFile(data).subscribe(
        (resp:any)=>{
          this.loading=0;
          var status=resp.Status[0];
          var message=resp.Message[0];
          var reportStatus=resp.ReportStatus[0];
          this.uploadSuc=0;
          this.uploadFail=0;
          this.noFileSelected=0;
          if(status==1){
            this.uploadSuc=1;
            this.uploadFail=0;
            this.noFileSelected=0;
            if(reportStatus==-1 || reportStatus==0){
              this.uploadFail=1;
              this.uploadSuc=0;
              this.reportGenerationMessage="Process Failed";
              setTimeout( () => {
                this.uploadFail = 0;
              }, 2000);
            }else if(reportStatus==1){
              this.uploadSuc=1;
              this.uploadFail=0;
              this.reportGenerationMessage="Report Generated Successfully";
              setTimeout( () => {
                this.uploadSuc = 0;
                this.goToOrderHistory();
              }, 2000);
            }else if(reportStatus==2){
              this.uploadSuc=1;
              this.uploadFail=0;
              this.reportGenerationMessage="Partially Report Generated";
              setTimeout( () => {
                this.uploadSuc = 0;
                this.goToOrderHistory();
              }, 2000);
            }
          }else{
            this.uploadSuc=0;
            this.noFileSelected=0;
            this.uploadFail=1;
            this.reportGenerationMessage="Error occured in report generation!";
            setTimeout( () => {
              this.uploadFail = 0;
              this.noFileSelected=0;
            }, 5000);
          }
        },(err)=>{
          this.loading=0;
          this.uploadSuc=0;
          this.uploadFail=0;
          this.noFileSelected=0;
          console.log("The error is ",err);
        })
      this.uploader.clearQueue();

    }
      
  }

  uploadFile(data: FormData): Observable<any> {
    return this.http.post(this.ConfigService.apiUrl + 'uploadFile.php', data);
  }

  ngOnInit() {
    this.uploadForm = this.fb.group({
      document: [null, null],
      type:  [null, Validators.compose([Validators.required])]
    });
  }
  goToOrderHistory(){
    this.router.navigateByUrl('/orderdetails');
  }

  downloadSampleFile(fileType){
    var sampleFileName='';
    if(fileType=='xls'){
      sampleFileName='sample.xls';
    }else if(fileType=='vcf'){
      sampleFileName='sample.vcf';
    }
    this.download.downloadSampleFile(fileType)
    .subscribe(
      (data:any)=>{
        saveAs(data, sampleFileName);
      },(error)=>{
        console.log("The error is ",error);

      }
    );
  }

}
