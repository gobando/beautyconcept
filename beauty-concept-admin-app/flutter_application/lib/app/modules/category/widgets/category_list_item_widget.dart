/*
 * File name: category_list_item_widget.dart
 * Last modified: 2022.02.17 at 10:49:48
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:get/get.dart';

import '../../../../common/ui.dart';
import '../../../models/category_model.dart';
import '../../../routes/app_routes.dart';

class CategoryListItemWidget extends StatelessWidget {
  final Category category;
  final String heroTag;
  final bool expanded;

  CategoryListItemWidget({Key key, this.category, this.heroTag, this.expanded}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: EdgeInsets.symmetric(horizontal: 20, vertical: 4),
      decoration: Ui.getBoxDecoration(
        border: Border.fromBorderSide(BorderSide.none),
        color: category.color.withOpacity(0.1),
      ),
      child: Theme(
        data: Get.theme.copyWith(dividerColor: Colors.transparent),
        child: ExpansionTile(
          initiallyExpanded: this.expanded,
          expandedCrossAxisAlignment: CrossAxisAlignment.stretch,
          title: InkWell(
              highlightColor: Colors.transparent,
              splashColor: Get.theme.colorScheme.secondary.withOpacity(0.08),
              onTap: () {
                Get.toNamed(Routes.CATEGORY, arguments: category);
              },
              child: Row(
                mainAxisAlignment: MainAxisAlignment.start,
                children: <Widget>[
                  Container(
                    padding: EdgeInsets.symmetric(vertical: 10),
                    width: 52,
                    height: 52,
                    child: (category.image.url.toLowerCase().endsWith('.svg')
                        ? SvgPicture.network(
                            category.image.url,
                            color: category.color,
                          )
                        : CachedNetworkImage(
                            fit: BoxFit.cover,
                            imageUrl: category.image.url,
                            placeholder: (context, url) => Image.asset(
                              'assets/img/loading.gif',
                              fit: BoxFit.cover,
                            ),
                            errorWidget: (context, url, error) => Icon(Icons.error_outline),
                          )),
                  ),
                  SizedBox(width: 10),
                  Expanded(
                    child: Text(
                      category.name,
                      overflow: TextOverflow.fade,
                      softWrap: false,
                      style: Get.textTheme.bodyText2,
                    ),
                  ),
                ],
              )),
          children: List.generate(category.subCategories?.length ?? 0, (index) {
            var _category = category.subCategories.elementAt(index);
            return GestureDetector(
              onTap: () {
                Get.toNamed(Routes.CATEGORY, arguments: _category);
              },
              child: Container(
                padding: EdgeInsets.symmetric(horizontal: 25, vertical: 16),
                child: Text(_category.name, style: Get.textTheme.bodyText1),
                decoration: BoxDecoration(
                  color: Get.theme.scaffoldBackgroundColor.withOpacity(0.2),
                  border: Border(top: BorderSide(color: Get.theme.scaffoldBackgroundColor.withOpacity(0.3))
                      //color: Get.theme.focusColor.withOpacity(0.2),
                      ),
                ),
              ),
            );
          }),
        ),
      ),
    );
  }
}
