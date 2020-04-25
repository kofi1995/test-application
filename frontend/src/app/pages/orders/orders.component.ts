import { Component, OnInit } from '@angular/core';
import {finalize} from "rxjs/operators";
import {DataService} from '../../services/data.service'
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import {NgbModal, NgbModalRef} from '@ng-bootstrap/ng-bootstrap';
import { faEdit } from '@fortawesome/free-solid-svg-icons';
import { faTrash } from '@fortawesome/free-solid-svg-icons';

@Component({
  selector: 'app-orders',
  templateUrl: './orders.component.html',
  styleUrls: ['./orders.component.scss']
})
export class OrdersComponent implements OnInit {
  orders: any = null;
  states: any;
  addOrderForm: FormGroup;
  editColumnForm: FormGroup;
  loading: boolean = false;
  loadingColumn: boolean = false;
  submitted: boolean = false;
  submittedColumn: boolean = false;
  openedModal: NgbModalRef;
  success: string = null;
  error: string = null;
  faEdit = faEdit;
  faTrash = faTrash;
  columnEditName: string = null;
  columnEditId: number= null;
  showEditForm: boolean = false;
  deleteOrderId: number;

  constructor(
    private formBuilder: FormBuilder,
    private dataService: DataService,
    private modalService: NgbModal,
  ) { }

  ngOnInit(): void {
    this.addOrderForm = this.formBuilder.group({
      name: ['', [Validators.required, Validators.min(1)]],
      zip: ['', [Validators.required, Validators.pattern("^\\d{5}$")]],
      state: ['', [Validators.required, Validators.minLength(2), Validators.maxLength(2)]],
      amount: ['', [Validators.required]],
      qty: ['', [Validators.required, Validators.min(1)]],
      item: ['', [Validators.required]],
    });
    this.initEditForm();
    this.getDrinks();
    this.states = this.dataService.getUsStates()
  }

  getDrinks() {
    this.dataService.getOrders()
      .pipe(finalize(() => {

      }))
      .subscribe(
        data => {
          this.orders = data
        },
        error => {
        });
  }
  get aof() { return this.addOrderForm.controls; }
  get ecf() { return this.editColumnForm.controls; }

  addOrderPopup(content) {
    this.openedModal = this.modalService.open(content, {ariaLabelledBy: 'modal-basic-title'});
  }

  addOrderSubmit() {
    this.submitted = true;
    this.success = this.error = null

    let order = {
      name: this.aof.name.value,
      state: this.aof.state.value,
      zip: this.aof.zip.value,
      qty: this.aof.qty.value,
      amount: this.aof.amount.value,
      item: this.aof.item.value
    }

    // stop here if form is invalid
    if (this.addOrderForm.invalid) {
      return;
    }
    this.loading = true;
    this.dataService.createOrder(order)
      .pipe(finalize(() => {
        this.loading = false;
      }))
      .subscribe(
        data => {
          this.success = data.data.message
          this.submitted = false
          this.openedModal.dismiss()
          setTimeout(function(){ location.reload(true); }, 100);
        },
        error => {
          if(error.error.data) {
            if(error.error.data.message) {
              this.error = error.error.data.message;
            }
          }
        });
  }

  editColumnSubmit(id:number, column:string) {
    this.submittedColumn = true;
    this.loadingColumn = true;
    if (this.editColumnForm.invalid) {
      return;
    }
    let data = {};
    data[column] = this.ecf.edit_var.value
    this.dataService.editOrder(id, data)
      .pipe(finalize(() => {
        this.loadingColumn = false;
        this.initEditForm();
      }))
      .subscribe(
        res => {
          this.hideEditColumn()
          this.submittedColumn = false
          this.updateActualColumnData(id, column, data[column])
        },
        error => {
          if(error.error.data) {
            if(error.error.data.message) {
              this.error = error.error.data.message;
              alert(this.error)
            }
          }
        });
  }

  showEditColumn(id:number, column:string, value:any) {
    this.showEditForm = true
    this.columnEditId = id
    this.columnEditName = column
    this.editColumnForm.patchValue({
      edit_var: value,
    });
  }

  hideEditColumn() {
    this.showEditForm = false
  }

  updateActualColumnData(id:number, column:string, value) {
    if(this.orders) {
      for (let order of this.orders) {
        if(order.id == id) {
          order[column] = value;
        }
      }
    }
  }

  showDeleteOrderModal(content, id:number) {
    this.deleteOrderId = id;
    this.openedModal = this.modalService.open(content, {ariaLabelledBy: 'modal-basic-title'});
  }
  deleteOrder(id:number) {
    this.dataService.deleteOrder(id)
      .subscribe(
        data => {
          this.openedModal.dismiss()
          setTimeout(function(){ location.reload(true); }, 100);
        },
        error => {
          if(error.error.data) {
            if(error.error.data.message) {
              this.error = error.error.data.message;
              alert(this.error)
            }
          }
        });
  }

  initEditForm() {
    this.editColumnForm = this.formBuilder.group({
      edit_var: [''],
    });
  }

}
