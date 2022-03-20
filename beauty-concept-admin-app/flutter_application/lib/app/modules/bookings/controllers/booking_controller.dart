/*
 * File name: booking_controller.dart
 * Last modified: 2022.02.26 at 14:50:11
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

import 'dart:async';

import 'package:get/get.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';

import '../../../../common/ui.dart';
import '../../../models/booking_model.dart';
import '../../../models/booking_status_model.dart';
import '../../../models/message_model.dart';
import '../../../models/user_model.dart';
import '../../../repositories/booking_repository.dart';
import '../../../repositories/salon_repository.dart';
import '../../../routes/app_routes.dart';
import '../../../services/global_service.dart';
import 'bookings_controller.dart';

class BookingController extends GetxController {
  SalonRepository _salonRepository;
  BookingRepository _bookingRepository;
  final allMarkers = <Marker>[].obs;
  final bookingStatuses = <BookingStatus>[].obs;
  Timer timer;
  GoogleMapController mapController;
  final booking = Booking().obs;

  BookingController() {
    _bookingRepository = BookingRepository();
    _salonRepository = SalonRepository();
  }

  @override
  void onInit() async {
    booking.value = Get.arguments as Booking;
    super.onInit();
  }

  @override
  void onReady() async {
    await refreshBooking();
    super.onReady();
  }

  Future refreshBooking({bool showMessage = false}) async {
    await getBooking();
    if (showMessage) {
      Get.showSnackbar(Ui.SuccessSnackBar(message: "Booking page refreshed successfully".tr));
    }
  }

  Future<void> getBooking() async {
    try {
      booking.value = await _bookingRepository.get(booking.value.id);
      if (booking.value.status == Get.find<BookingsController>().getStatusByOrder(Get.find<GlobalService>().global.value.inProgress) && timer == null) {
        timer = Timer.periodic(Duration(minutes: 1), (t) {
          booking.update((val) {
            val.duration += (1 / 60);
          });
        });
      }
    } catch (e) {
      Get.showSnackbar(Ui.ErrorSnackBar(message: e.toString()));
    }
  }

  Future<void> startBookingService() async {
    try {
      final _status = Get.find<BookingsController>().getStatusByOrder(Get.find<GlobalService>().global.value.inProgress);
      final _booking = new Booking(id: booking.value.id, startAt: DateTime.now(), status: _status);
      await _bookingRepository.update(_booking);
      booking.update((val) {
        val.startAt = _booking.startAt;
        val.status = _status;
      });
      timer = Timer.periodic(Duration(minutes: 1), (t) {
        booking.update((val) {
          val.duration += (1 / 60);
        });
      });
    } catch (e) {
      Get.showSnackbar(Ui.ErrorSnackBar(message: e.toString()));
    }
  }

  Future<void> finishBookingService() async {
    try {
      final _status = Get.find<BookingsController>().getStatusByOrder(Get.find<GlobalService>().global.value.done);
      var _booking = new Booking(id: booking.value.id, endsAt: DateTime.now(), status: _status);
      final result = await _bookingRepository.update(_booking);
      booking.update((val) {
        val.endsAt = result.endsAt;
        val.duration = result.duration;
        val.status = _status;
      });
      timer?.cancel();
    } catch (e) {
      Get.showSnackbar(Ui.ErrorSnackBar(message: e.toString()));
    }
  }

  Future<void> cancelBookingService() async {
    try {
      if (booking.value.status.order < Get.find<GlobalService>().global.value.onTheWay) {
        final _status = Get.find<BookingsController>().getStatusByOrder(Get.find<GlobalService>().global.value.failed);
        final _booking = new Booking(id: booking.value.id, cancel: true, status: _status);
        await _bookingRepository.update(_booking);
        booking.update((val) {
          val.cancel = true;
          val.status = _status;
        });
      }
    } catch (e) {
      Get.showSnackbar(Ui.ErrorSnackBar(message: e.toString()));
    }
  }

  String getTime({String separator = ":"}) {
    String hours = "";
    String minutes = "";
    int minutesInt = ((booking.value.duration - booking.value.duration.toInt()) * 60).toInt();
    int hoursInt = booking.value.duration.toInt();
    if (hoursInt < 10) {
      hours = "0" + hoursInt.toString();
    } else {
      hours = hoursInt.toString();
    }
    if (minutesInt < 10) {
      minutes = "0" + minutesInt.toString();
    } else {
      minutes = minutesInt.toString();
    }
    return hours + separator + minutes;
  }

  Future<void> startChat() async {
    List<User> _employees = await _salonRepository.getEmployees(booking.value.salon.id);
    _employees = _employees
        .map((e) {
          e.avatar = booking.value.salon.images[0];
          return e;
        })
        .toSet()
        .toList();
    Message _message = new Message(_employees, name: booking.value.salon.name);
    Get.toNamed(Routes.CHAT, arguments: _message);
  }
}
