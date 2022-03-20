/*
 * File name: salon_thumbs_widget.dart
 * Last modified: 2022.02.17 at 09:47:19
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

import 'dart:math';

import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';

import '../../../models/salon_model.dart';

class SalonThumbsWidget extends StatelessWidget {
  const SalonThumbsWidget({
    Key key,
    @required Salon salon,
  })  : _salon = salon,
        super(key: key);

  final Salon _salon;

  @override
  Widget build(BuildContext context) {
    return Wrap(
        spacing: 2,
        children: List.generate(
          min(_salon.images.length, 4),
          (index) {
            return CachedNetworkImage(
              height: 60,
              width: 68.5,
              fit: BoxFit.cover,
              imageUrl: _salon.images.reversed.elementAt(index).icon,
              placeholder: (context, url) => Image.asset(
                'assets/img/loading.gif',
                fit: BoxFit.cover,
                width: double.infinity,
                height: 100,
              ),
              errorWidget: (context, url, error) => Icon(Icons.error_outline),
            );
          },
        ));
  }
}
