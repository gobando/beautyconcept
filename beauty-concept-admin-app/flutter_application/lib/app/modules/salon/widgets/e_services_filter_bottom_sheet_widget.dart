/*
 * File name: e_services_filter_bottom_sheet_widget.dart
 * Last modified: 2022.02.06 at 18:50:56
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

import 'package:flutter/material.dart';
import 'package:get/get.dart';

import '../../global_widgets/circular_loading_widget.dart';
import '../controllers/salon_e_services_controller.dart';

class EServicesFilterBottomSheetWidget extends GetView<SalonEServicesController> {
  @override
  Widget build(BuildContext context) {
    return Container(
      height: Get.height - 90,
      decoration: BoxDecoration(
        color: Get.theme.primaryColor,
        borderRadius: BorderRadius.only(topRight: Radius.circular(20), topLeft: Radius.circular(20)),
        boxShadow: [
          BoxShadow(color: Get.theme.focusColor.withOpacity(0.4), blurRadius: 30, offset: Offset(0, -30)),
        ],
      ),
      child: Stack(
        children: <Widget>[
          Padding(
            padding: const EdgeInsets.only(top: 100),
            child: ListView(
              padding: EdgeInsets.only(top: 20, bottom: 15, left: 4, right: 4),
              children: [
                Obx(() {
                  if (controller.categories.isEmpty) {
                    return CircularLoadingWidget(height: 100);
                  }
                  return ExpansionTile(
                    title: Text("Categories".tr, style: Get.textTheme.bodyText2),
                    children: List.generate(controller.categories.length, (index) {
                      var _category = controller.categories.elementAt(index);
                      return Obx(() {
                        return CheckboxListTile(
                          activeColor: Get.theme.colorScheme.secondary,
                          controlAffinity: ListTileControlAffinity.trailing,
                          dense: true,
                          value: controller.selectedCategories.contains(_category.id),
                          onChanged: (value) {
                            print(value);
                            controller.toggleCategory(value, _category);
                          },
                          title: Text(
                            _category.name,
                            style: Get.textTheme.bodyText1,
                            overflow: TextOverflow.fade,
                            softWrap: false,
                            maxLines: 1,
                          ),
                        );
                      });
                    }),
                    initiallyExpanded: true,
                  );
                }),
                ExpansionTile(
                  title: Text("Sort & Refine".tr, style: Get.textTheme.bodyText2),
                  children: List.generate(CategoryFilter.values.length, (index) {
                    var _filter = CategoryFilter.values.elementAt(index);
                    return Obx(() {
                      return CheckboxListTile(
                        activeColor: Get.theme.colorScheme.secondary,
                        dense: true,
                        controlAffinity: ListTileControlAffinity.trailing,
                        value: controller.selected.value == _filter,
                        onChanged: (value) {
                          controller.toggleSelected(_filter);
                          print(value);
                        },
                        title: Text(
                          _filter.toString().tr,
                          style: Get.textTheme.bodyText1,
                          overflow: TextOverflow.fade,
                          softWrap: false,
                          maxLines: 1,
                        ),
                      );
                    });
                  }),
                  initiallyExpanded: true,
                ),
              ],
            ),
          ),
          Container(
            padding: EdgeInsets.symmetric(horizontal: 20, vertical: 50),
            child: Row(
              children: [
                Expanded(child: Text("Filter".tr, style: Get.textTheme.headline5)),
                MaterialButton(
                  onPressed: () {
                    controller.loadEServicesOfCategory();
                    Get.back();
                  },
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                  color: Get.theme.colorScheme.secondary.withOpacity(0.15),
                  child: Text("Apply".tr, style: Get.textTheme.subtitle1),
                  elevation: 0,
                ),
              ],
            ),
          ),
          Container(
            height: 30,
            width: double.infinity,
            padding: EdgeInsets.symmetric(vertical: 13, horizontal: (Get.width / 2) - 30),
            decoration: BoxDecoration(
              color: Get.theme.focusColor.withOpacity(0.1),
              borderRadius: BorderRadius.only(topRight: Radius.circular(20), topLeft: Radius.circular(20)),
            ),
            child: Container(
              decoration: BoxDecoration(
                color: Get.theme.focusColor.withOpacity(0.5),
                borderRadius: BorderRadius.circular(3),
              ),
              //child: SizedBox(height: 1,),
            ),
          ),
        ],
      ),
    );
  }
}
