import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import {OrdersComponent} from './pages/orders/orders.component';
import {NotFoundComponent} from './pages/not-found/not-found.component';


const routes: Routes = [
  {path:  '', pathMatch:  "full",redirectTo:  "/orders"},
  { path: 'home', pathMatch:  "full",redirectTo:  "/orders"},
  { path: 'orders', component: OrdersComponent},
  {path: '404', component: NotFoundComponent},
  {path: '**', redirectTo: '/404'}
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
