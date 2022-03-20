/*
 * File name: book_e_service_controller.dart
 * Last modified: 2022.02.23 at 11:42:19
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

import 'package:date_picker_timeline/date_picker_timeline.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:intl/intl.dart' show DateFormat;

import '../../../../common/ui.dart';
import '../../../models/address_model.dart';
import '../../../models/booking_model.dart';
import '../../../models/coupon_model.dart';
import '../../../models/user_model.dart';
import '../../../repositories/booking_repository.dart';
import '../../../repositories/salon_repository.dart';
import '../../../repositories/setting_repository.dart';
import '../../../services/auth_service.dart';
import '../../../services/settings_service.dart';
import '../../global_widgets/tab_bar_widget.dart';

class BookEServiceController extends GetxController {
  final atSalon = true.obs;
  final booking = Booking().obs;
  final addresses = <Address>[].obs;
  final morningTimes = [].obs;
  final afternoonTimes = [].obs;
  final eveningTimes = [].obs;
  final nightTimes = [].obs;
  BookingRepository _bookingRepository;
  SalonRepository _salonRepository;
  SettingRepository _settingRepository;
  DatePickerController datePickerController = DatePickerController();

  Address get currentAddress => Get.find<SettingsService>().address.value;

  BookEServiceController() {
    _bookingRepository = BookingRepository();
    _salonRepository = SalonRepository();
    _settingRepository = SettingRepository();
  }

  @override
  void onInit() async {
    final _booking = (Get.arguments['booking'] as Booking);
    this.booking.value = Booking(
      eServices: _booking.eServices,
      salon: _booking.salon,
      taxes: _booking.salon.taxes,
      options: _booking.options,
      quantity: _booking.quantity,
      user: Get.find<AuthService>().user.value,
      coupon: new Coupon(),
    );
    await getAddresses();
    await getTimes();
    super.onInit();
  }

  void toggleAtSalon(value) {
    atSalon.value = value;
  }

  TextStyle getTextTheme(bool selected) {
    if (selected) {
      return Get.textTheme.bodyText2.merge(TextStyle(color: Get.theme.primaryColor));
    }
    return Get.textTheme.bodyText2;
  }

  Color getColor(bool selected) {
    if (selected) {
      return Get.theme.colorScheme.secondary;
    }
    return null;
  }

  Future getAddresses() async {
    try {
      if (Get.find<AuthService>().isAuth) {
        addresses.assignAll(await _settingRepository.getAddresses());
        if (!currentAddress.isUnknown()) {
          addresses.remove(currentAddress);
          addresses.insert(0, currentAddress);
        }
        if (Get.isRegistered<TabBarController>(tag: 'addresses')) {
          Get.find<TabBarController>(tag: 'addresses').selectedId.value = addresses.elementAt(0).id;
        }
      }
    } catch (e) {
      Get.showSnackbar(Ui.ErrorSnackBar(message: e.toString()));
    }
  }

  Future getTimes({DateTime date}) async {
    try {
      nightTimes.clear();
      morningTimes.clear();
      afternoonTimes.clear();
      eveningTimes.clear();
      List<dynamic> times = await _salonRepository.getAvailabilityHours(this.booking.value.salon.id, date ?? DateTime.now(), this.booking.value.employee?.id);
      times.sort((e1, e2) {
        final _localDateTime1 = DateTime.parse(e1.elementAt(0)).toLocal();
        final hours1 = int.tryParse(DateFormat('HH').format(_localDateTime1));
        final _localDateTime2 = DateTime.parse(e2.elementAt(0)).toLocal();
        final hours2 = int.tryParse(DateFormat('HH').format(_localDateTime2));
        return hours1.compareTo(hours2);
      });
      nightTimes.assignAll(times.sublist(0, 14));
      morningTimes.assignAll(times.sublist(14, 24));
      afternoonTimes.assignAll(times.sublist(24, 36));
      eveningTimes.assignAll(times.sublist(36));
    } catch (e) {
      Get.showSnackbar(Ui.ErrorSnackBar(message: e.toString()));
    }
  }

  void validateCoupon() async {
    try {
      FocusManager.instance.primaryFocus?.unfocus();
      Coupon _coupon = await _bookingRepository.coupon(booking.value);
      booking.update((val) {
        val.coupon = _coupon;
      });
      if (_coupon.hasData) {
        Get.showSnackbar(Ui.SuccessSnackBar(message: "Coupon code is applied".tr, snackPosition: SnackPosition.TOP));
      } else {
        Get.showSnackbar(Ui.ErrorSnackBar(message: "Invalid Coupon Code".tr, snackPosition: SnackPosition.TOP));
      }
    } catch (e) {
      Get.showSnackbar(Ui.ErrorSnackBar(message: e.toString()));
    }
  }

  void selectEmployee(User employee) async {
    booking.update((val) {
      if (val.employee == null) {
        val.employee = employee;
      } else {
        val.employee = null;
      }
    });
    if (booking.value.bookingAt != null) {
      await getTimes(date: booking.value.bookingAt);
    }
  }

  bool isCheckedEmployee(User user) {
    return (booking.value.employee?.id ?? '0') == user.id;
  }

  TextStyle getTitleTheme(User user) {
    if (isCheckedEmployee(user)) {
      return Get.textTheme.bodyText2.merge(TextStyle(color: Get.theme.colorScheme.secondary));
    }
    return Get.textTheme.bodyText2;
  }

  TextStyle getSubTitleTheme(User user) {
    if (isCheckedEmployee(user)) {
      return Get.textTheme.caption.merge(TextStyle(color: Get.theme.colorScheme.secondary));
    }
    return Get.textTheme.caption;
  }
}
