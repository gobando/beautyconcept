/*
 * File name: maps_controller.dart
 * Last modified: 2022.02.26 at 14:50:11
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

import 'dart:async';
import 'dart:math';
import 'dart:typed_data';
import 'dart:ui' as ui;

import 'package:flutter/services.dart';
import 'package:get/get.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:location/location.dart' as LocationPlatformInterface;

import '../../../../common/ui.dart';
import '../../../models/address_model.dart';
import '../../../models/salon_model.dart';
import '../../../repositories/salon_repository.dart';
import '../../../services/settings_service.dart';

class MapsController extends GetxController {
  final salons = <Salon>[].obs;
  final allMarkers = <Marker>[].obs;
  final cameraPosition = new CameraPosition(target: LatLng(0, 0)).obs;
  final mapController = Rx<GoogleMapController>(null);
  LocationPlatformInterface.Location location = new LocationPlatformInterface.Location();
  LocationPlatformInterface.PermissionStatus permissionGranted = LocationPlatformInterface.PermissionStatus.denied;
  bool isLocationServiceEnabled = false;
  SalonRepository _salonRepository;

  MapsController() {
    _salonRepository = new SalonRepository();
  }

  Address get currentAddress => Get.find<SettingsService>().address.value;

  @override
  void onInit() async {
    await refreshMaps();
    super.onInit();
  }

  Future refreshMaps({bool showMessage = false}) async {
    await getCurrentPosition();
    if (showMessage) {
      Get.showSnackbar(Ui.SuccessSnackBar(message: "Home page refreshed successfully".tr));
    }
  }

  Future<void> getCurrentPosition() async {
    cameraPosition.value = CameraPosition(
      target: currentAddress.getLatLng(),
      zoom: 14.4746,
    );
    Marker marker = await _getMyPositionMarker(currentAddress.getLatLng());
    allMarkers.add(marker);
  }

  Future getNearSalons() async {
    try {
      salons.clear();
      salons.assignAll(await _salonRepository.getNearSalons(currentAddress.getLatLng(), cameraPosition.value.target));
      salons.forEach((element) async {
        var salonMarket = await getSalonMarker(element);
        allMarkers.add(salonMarket);
      });
    } catch (e) {
      Get.showSnackbar(Ui.ErrorSnackBar(message: e.message));
    }
  }

  Future<Uint8List> _getBytesFromAsset(String path, int width) async {
    ByteData data = await rootBundle.load(path);
    ui.Codec codec = await ui.instantiateImageCodec(data.buffer.asUint8List(), targetWidth: width);
    ui.FrameInfo fi = await codec.getNextFrame();
    return (await fi.image.toByteData(format: ui.ImageByteFormat.png)).buffer.asUint8List();
  }

  Future<Marker> _getMyPositionMarker(LatLng latLng) async {
    final Uint8List markerIcon = await _getBytesFromAsset('assets/img/my_marker.png', 120);
    final Marker marker = Marker(markerId: MarkerId(Random().nextInt(100).toString()), icon: BitmapDescriptor.fromBytes(markerIcon), anchor: ui.Offset(0.5, 0.5), position: latLng);

    return marker;
  }

  Future<Marker> getSalonMarker(Salon salon) async {
    final Uint8List markerIcon = await _getBytesFromAsset('assets/img/marker.png', 120);
    final Marker marker = Marker(
      markerId: MarkerId(salon.id),
      icon: BitmapDescriptor.fromBytes(markerIcon),
//        onTap: () {
//          //print(res.name);
//        },
      anchor: ui.Offset(0.5, 0.5),
      infoWindow: InfoWindow(
          title: salon.name,
          snippet: Ui.getDistance(salon.distance),
          onTap: () {
            //print(CustomTrace(StackTrace.current, message: 'Info Window'));
          }),
      position: salon.address.getLatLng(),
    );

    return marker;
  }
}
