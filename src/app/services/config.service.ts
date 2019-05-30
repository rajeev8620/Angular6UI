import { Injectable } from '@angular/core';

@Injectable({ providedIn: 'root' })
export class ConfigService {
   apiUrl = 'http://localhost/MelanomaPredictor_API/';
    //  apiUrl = 'http://3.218.66.136/MelanomaPredictor_API/';
    constructor() { }


}