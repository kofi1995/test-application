import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { BehaviorSubject, Observable} from 'rxjs';
import { map } from 'rxjs/operators';
import { environment } from '../environments/env';

@Injectable({
  providedIn: 'root'
})
export class DataService {

  private ordersSubject: BehaviorSubject<any>;

  constructor(private http: HttpClient) {
    this.ordersSubject = new BehaviorSubject<any>(null);
  }

  public get orders(): any {
    return this.ordersSubject.value
}
  getOrders() {
    return this.http.get<any>(`${environment.apiUrl}/orders`)
        .pipe(map(orders => {
            this.ordersSubject.next(orders.data.data);
            return orders.data.data;
        }));
  }
  createOrder(order) {
    return this.http.post<any>(`${environment.apiUrl}/orders/store`, order)
      .pipe(map(res => {
        return res;
      }));
  }
  editOrder(id:number, order) {
    return this.http.post<any>(`${environment.apiUrl}/orders/edit/${id}`, order)
      .pipe(map(res => {
        return res;
      }));
  }
  deleteOrder(id:number) {
    return this.http.post<any>(`${environment.apiUrl}/orders/delete/${id}`, {})
      .pipe(map(res => {
        return res;
      }));
  }


  getUsStates() {
    return [
      {
        "name": "Alabama",
        "abbreviation": "AL"
      },
      {
        "name": "Alaska",
        "abbreviation": "AK"
      },
      {
        "name": "American Samoa",
        "abbreviation": "AS"
      },
      {
        "name": "Arizona",
        "abbreviation": "AZ"
      },
      {
        "name": "Arkansas",
        "abbreviation": "AR"
      },
      {
        "name": "California",
        "abbreviation": "CA"
      },
      {
        "name": "Colorado",
        "abbreviation": "CO"
      },
      {
        "name": "Connecticut",
        "abbreviation": "CT"
      },
      {
        "name": "Delaware",
        "abbreviation": "DE"
      },
      {
        "name": "District Of Columbia",
        "abbreviation": "DC"
      },
      {
        "name": "Federated States Of Micronesia",
        "abbreviation": "FM"
      },
      {
        "name": "Florida",
        "abbreviation": "FL"
      },
      {
        "name": "Georgia",
        "abbreviation": "GA"
      },
      {
        "name": "Guam",
        "abbreviation": "GU"
      },
      {
        "name": "Hawaii",
        "abbreviation": "HI"
      },
      {
        "name": "Idaho",
        "abbreviation": "ID"
      },
      {
        "name": "Illinois",
        "abbreviation": "IL"
      },
      {
        "name": "Indiana",
        "abbreviation": "IN"
      },
      {
        "name": "Iowa",
        "abbreviation": "IA"
      },
      {
        "name": "Kansas",
        "abbreviation": "KS"
      },
      {
        "name": "Kentucky",
        "abbreviation": "KY"
      },
      {
        "name": "Louisiana",
        "abbreviation": "LA"
      },
      {
        "name": "Maine",
        "abbreviation": "ME"
      },
      {
        "name": "Marshall Islands",
        "abbreviation": "MH"
      },
      {
        "name": "Maryland",
        "abbreviation": "MD"
      },
      {
        "name": "Massachusetts",
        "abbreviation": "MA"
      },
      {
        "name": "Michigan",
        "abbreviation": "MI"
      },
      {
        "name": "Minnesota",
        "abbreviation": "MN"
      },
      {
        "name": "Mississippi",
        "abbreviation": "MS"
      },
      {
        "name": "Missouri",
        "abbreviation": "MO"
      },
      {
        "name": "Montana",
        "abbreviation": "MT"
      },
      {
        "name": "Nebraska",
        "abbreviation": "NE"
      },
      {
        "name": "Nevada",
        "abbreviation": "NV"
      },
      {
        "name": "New Hampshire",
        "abbreviation": "NH"
      },
      {
        "name": "New Jersey",
        "abbreviation": "NJ"
      },
      {
        "name": "New Mexico",
        "abbreviation": "NM"
      },
      {
        "name": "New York",
        "abbreviation": "NY"
      },
      {
        "name": "North Carolina",
        "abbreviation": "NC"
      },
      {
        "name": "North Dakota",
        "abbreviation": "ND"
      },
      {
        "name": "Northern Mariana Islands",
        "abbreviation": "MP"
      },
      {
        "name": "Ohio",
        "abbreviation": "OH"
      },
      {
        "name": "Oklahoma",
        "abbreviation": "OK"
      },
      {
        "name": "Oregon",
        "abbreviation": "OR"
      },
      {
        "name": "Palau",
        "abbreviation": "PW"
      },
      {
        "name": "Pennsylvania",
        "abbreviation": "PA"
      },
      {
        "name": "Puerto Rico",
        "abbreviation": "PR"
      },
      {
        "name": "Rhode Island",
        "abbreviation": "RI"
      },
      {
        "name": "South Carolina",
        "abbreviation": "SC"
      },
      {
        "name": "South Dakota",
        "abbreviation": "SD"
      },
      {
        "name": "Tennessee",
        "abbreviation": "TN"
      },
      {
        "name": "Texas",
        "abbreviation": "TX"
      },
      {
        "name": "Utah",
        "abbreviation": "UT"
      },
      {
        "name": "Vermont",
        "abbreviation": "VT"
      },
      {
        "name": "Virgin Islands",
        "abbreviation": "VI"
      },
      {
        "name": "Virginia",
        "abbreviation": "VA"
      },
      {
        "name": "Washington",
        "abbreviation": "WA"
      },
      {
        "name": "West Virginia",
        "abbreviation": "WV"
      },
      {
        "name": "Wisconsin",
        "abbreviation": "WI"
      },
      {
        "name": "Wyoming",
        "abbreviation": "WY"
      }
    ]
  }
}
