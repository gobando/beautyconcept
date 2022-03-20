/*
 * File name: salon_binding.dart
 * Last modified: 2022.02.06 at 16:15:28
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

import 'package:get/get.dart';

import '../../messages/controllers/messages_controller.dart';
import '../controllers/salon_awards_controller.dart';
import '../controllers/salon_controller.dart';
import '../controllers/salon_e_services_controller.dart';
import '../controllers/salon_experiences_controller.dart';
import '../controllers/salon_galleries_controller.dart';
import '../controllers/salon_reviews_controller.dart';

class SalonBinding extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut<SalonController>(
      () => SalonController(),
    );
    Get.lazyPut<SalonAwardsController>(
      () => SalonAwardsController(),
    );
    Get.lazyPut<SalonExperiencesController>(
      () => SalonExperiencesController(),
    );
    Get.lazyPut<SalonGalleriesController>(
      () => SalonGalleriesController(),
    );
    Get.lazyPut<SalonReviewsController>(
      () => SalonReviewsController(),
    );
    Get.lazyPut<SalonEServicesController>(
      () => SalonEServicesController(),
    );
    Get.lazyPut<SalonEServicesController>(
      () => SalonEServicesController(),
    );
    Get.lazyPut<MessagesController>(
      () => MessagesController(),
    );
  }
}
