/*
 * File name: salon_controller.dart
 * Last modified: 2022.02.12 at 21:57:18
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

import 'package:flutter/cupertino.dart';
import 'package:get/get.dart';

import '../../../../common/ui.dart';
import '../../../models/message_model.dart';
import '../../../models/salon_model.dart';
import '../../../models/user_model.dart';
import '../../../repositories/salon_repository.dart';
import '../../../routes/app_routes.dart';
import '../../global_widgets/tab_bar_widget.dart';
import '../views/salon_awards_view.dart';
import '../views/salon_details_view.dart';
import '../views/salon_e_services_view.dart';
import '../views/salon_experiences_view.dart';
import '../views/salon_galleries_view.dart';
import '../views/salon_reviews_view.dart';

class SalonController extends GetxController {
  final salon = Salon().obs;
  final currentSlide = 0.obs;
  var currentIndex = 0.obs;
  List<Widget> pages = [
    SalonDetailsView(),
    SalonEServicesView(),
    SalonGalleriesView(),
    SalonReviewsView(),
    SalonAwardsView(),
    SalonExperiencesView(),
  ];

  String heroTag = "";
  SalonRepository _salonRepository;

  SalonController() {
    _salonRepository = new SalonRepository();
  }

  @override
  void onInit() {
    if (Get.isRegistered<TabBarController>(tag: 'salon')) {
      Get.find<TabBarController>(tag: 'salon').selectedId.value = '0';
    }
    var arguments = Get.arguments as Map<String, dynamic>;
    salon.value = arguments['salon'] as Salon;
    heroTag = arguments['heroTag'] as String;
    currentIndex.value = 0;
    super.onInit();
  }

  @override
  void onReady() async {
    await refreshSalon();
    super.onReady();
  }

  Widget get currentPage => pages[currentIndex.value];

  void changePage(int index) {
    currentIndex.value = index;
  }

  Future refreshSalon({bool showMessage = false}) async {
    await getSalon();
    if (showMessage) {
      Get.showSnackbar(Ui.SuccessSnackBar(message: salon.value.name + " " + "page refreshed successfully".tr));
    }
  }

  Future getSalon() async {
    try {
      var distance = salon.value.distance;
      salon.value = await _salonRepository.get(salon.value.id);
      salon.value.distance = distance;
    } catch (e) {
      Get.showSnackbar(Ui.ErrorSnackBar(message: e.toString()));
    }
  }

  void startChat() {
    List<User> _employees = salon.value.employees.map((e) {
      e.avatar = salon.value.images[0];
      return e;
    }).toList();
    Message _message = new Message(_employees, name: salon.value.name);
    Get.toNamed(Routes.CHAT, arguments: _message);
  }
}
