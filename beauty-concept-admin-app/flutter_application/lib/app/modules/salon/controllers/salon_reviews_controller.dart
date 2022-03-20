/*
 * File name: salon_reviews_controller.dart
 * Last modified: 2022.02.12 at 21:52:29
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

import 'package:get/get.dart';

import '../../../../common/ui.dart';
import '../../../models/review_model.dart';
import '../../../models/salon_model.dart';
import '../../../repositories/salon_repository.dart';

class SalonReviewsController extends GetxController {
  final salon = Salon().obs;
  final reviews = <Review>[].obs;

  SalonRepository _salonRepository;

  SalonReviewsController() {
    _salonRepository = new SalonRepository();
  }

  @override
  void onInit() {
    var arguments = Get.arguments as Map<String, dynamic>;
    salon.value = arguments['salon'] as Salon;
    super.onInit();
  }

  @override
  void onReady() async {
    await refreshReviews();
    super.onReady();
  }

  Future refreshReviews({bool showMessage = false}) async {
    await getReviews();
    if (showMessage) {
      Get.showSnackbar(Ui.SuccessSnackBar(message: salon.value.name + " " + "page refreshed successfully".tr));
    }
  }

  Future getReviews() async {
    try {
      reviews.assignAll(await _salonRepository.getReviews(salon.value.id));
    } catch (e) {
      Get.showSnackbar(Ui.ErrorSnackBar(message: e.toString()));
    }
  }
}
