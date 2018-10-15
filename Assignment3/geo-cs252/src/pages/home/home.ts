import { Component, ViewChild, ElementRef } from '@angular/core';
import { NavController } from 'ionic-angular';

import { Geolocation } from '@ionic-native/geolocation';

declare var google;

@Component({
  selector: 'page-home',
  templateUrl: 'home.html'
})
export class HomePage {
  lat: any;
  lng: any;
  @ViewChild('map') mapElement: ElementRef;
  map: any;
  
  changeCoord(lat: any, lng: any): void {
    console.log(lat, lng);
    this.lat = lat;
    this.lng = lng;
    let latLng = new google.maps.LatLng(this.lat, this.lng);
    this.map.setCenter(latLng);
    this.map.setZoom(16);
  }

  resetCoords(): void {
   this.geo.getCurrentPosition().then(pos => {
      this.lat = pos.coords.latitude;
      this.lng = pos.coords.longitude;
      let latLng = new google.maps.LatLng(this.lat, this.lng);
      this.map.setCenter(latLng);
      this.map.setZoom(16);
    }).catch(err => console.log(err));
  }

  constructor(public navCtrl: NavController, public geo: Geolocation) {
  }

  ionViewDidLoad() {
    let mapOptions = {
      zoom: 13,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      mapTypeControl: false,
      streetViewControl: false,
      fullscreenControl: false
    }
    this.map = new google.maps.Map(this.mapElement.nativeElement, mapOptions);
    this.geo.getCurrentPosition().then(pos => {
      this.lat = pos.coords.latitude;
      this.lng = pos.coords.longitude;
      let latLng = new google.maps.LatLng(this.lat, this.lng);
      this.map.setCenter(latLng);
      this.map.setZoom(16);
    }).catch(err => console.log(err));
  }

}
