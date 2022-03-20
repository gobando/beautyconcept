/*
 * File name: stripe_fpx_controller.dart
 * Last modified: 2022.02.09 at 17:17:00
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

import 'package:get/get.dart';
import 'package:webview_flutter/webview_flutter.dart';

import '../../../../common/helper.dart';
import '../../../models/booking_model.dart';
import '../../../repositories/payment_repository.dart';
import '../../../routes/app_routes.dart';
import '../../../services/global_service.dart';
import '../../bookings/controllers/bookings_controller.dart';
import '../../global_widgets/tab_bar_widget.dart';

class StripeFPXController extends GetxController {
  WebViewController webView;
  PaymentRepository _paymentRepository;
  final url = "".obs;
  final progress = 0.0.obs;
  final booking = new Booking().obs;

  StripeFPXController() {
    _paymentRepository = new PaymentRepository();
  }

  @override
  void onInit() {
    booking.value = Get.arguments['booking'] as Booking;
    getUrl();
    super.onInit();
  }

  void getUrl() {
    url.value = _paymentRepository.getStripeFPXUrl(booking.value);
  }

  void showConfirmationIfSuccess() {
    final _doneUrl = "${Helper.toUrl(Get.find<GlobalService>().baseUrl)}payments/stripe-fpx";
    if (url == _doneUrl) {
      Get.find<BookingsController>().currentStatus.value = Get.find<BookingsController>().getStatusByOrder(1).id;
      if (Get.isRegistered<TabBarController>(tag: 'bookings')) {
        Get.find<TabBarController>(tag: 'bookings').selectedId.value = Get.find<BookingsController>().getStatusByOrder(1).id;
      }
      Get.toNamed(Routes.CONFIRMATION, arguments: {
        'title': "Payment Successful".tr,
        'long_message': "Your Payment is Successful".tr,
      });
    }
  }
}
