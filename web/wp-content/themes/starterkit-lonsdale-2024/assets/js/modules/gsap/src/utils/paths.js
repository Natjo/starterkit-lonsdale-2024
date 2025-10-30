let _svgPathExp = /[achlmqstvz]|(-?\d*\.?\d*(?:e[\-+]?\d+)?)[0-9]/ig,
  _numbersExp = /(?:(-)?\d*\.?\d*(?:e[\-+]?\d+)?)[0-9]/ig,
  _scientific = /[\+\-]?\d*\.?\d+e[\+\-]?\d+/ig,
  _selectorExp = /(^[#\.][a-z]|[a-y][a-z])/i,
  _DEG2RAD = Math.PI / 180,
  _RAD2DEG = 180 / Math.PI,
  _sin = Math.sin,
  _cos = Math.cos,
  _abs = Math.abs,
  _sqrt = Math.sqrt,
  _atan2 = Math.atan2,
  _largeNum = 1e8,
  _isString = value => typeof value === "string",
  _isNumber = value => typeof value === "number",
  _isUndefined = value => typeof value === "undefined",
  _temp = {},
  _temp2 = {},
  _roundingNum = 1e5,
  _wrapProgress = progress => Math.round((progress + _largeNum) % 1 * _roundingNum) / _roundingNum || (progress < 0 ? 0 : 1),
  _round = value => Math.round(value * _roundingNum) / _roundingNum || 0,
  _roundPrecise = value => Math.round(value * 1e10) / 1e10 || 0,
  _splitSegment = (rawPath, segIndex, i, t) => {
    let segment = rawPath[segIndex],
      shift = t === 1 ? 6 : subdivideSegment(segment, i, t);
    if ((shift || !t) && shift + i + 2 < segment.length) {
      rawPath.splice(segIndex, 0, segment.slice(0, i + shift + 2));
      segment.splice(0, i + shift);
      return 1;
    }
  },
  _getSampleIndex = (samples, length, progress) => {
    let l = samples.length,
      i = ~~(progress * l);
    if (samples[i] > length) {
      while (--i && samples[i] > length) {}
      i < 0 && (i = 0);
    } else {
      while (samples[++i] < length && i < l) {}
    }
    return i < l ? i : l - 1;
  },
  _reverseRawPath = (rawPath, skipOuter) => {
    let i = rawPath.length;
    skipOuter || rawPath.reverse();
    while (i--) {
      rawPath[i].reversed || reverseSegment(rawPath[i]);
    }
  },
  _copyMetaData = (source, copy) => {
    copy.totalLength = source.totalLength;
    if (source.samples) {
      copy.samples = source.samples.slice(0);
      copy.lookup = source.lookup.slice(0);
      copy.minLength = source.minLength;
      copy.resolution = source.resolution;
    } else if (source.totalPoints) {
      copy.totalPoints = source.totalPoints;
    }
    return copy;
  },
  _appendOrMerge = (rawPath, segment) => {
    let index = rawPath.length,
      prevSeg = rawPath[index - 1] || [],
      l = prevSeg.length;
    if (index && segment[0] === prevSeg[l - 2] && segment[1] === prevSeg[l - 1]) {
      segment = prevSeg.concat(segment.slice(2));
      index--;
    }
    rawPath[index] = segment;
  },
  _bestDistance;
export function getRawPath(value) {
  value = _isString(value) && _selectorExp.test(value) ? document.querySelector(value) || value : value;
  let e = value.getAttribute ? value : 0,
    rawPath;
  if (e && (value = value.getAttribute("d"))) {
    if (!e._gsPath) {
      e._gsPath = {};
    }
    rawPath = e._gsPath[value];
    return rawPath && !rawPath._dirty ? rawPath : e._gsPath[value] = stringToRawPath(value);
  }
  return !value ? console.warn("Expecting a <path> element or an SVG path data string") : _isString(value) ? stringToRawPath(value) : _isNumber(value[0]) ? [value] : value;
}
export function copyRawPath(rawPath) {
  let a = [],
    i = 0;
  for (; i < rawPath.length; i++) {
    a[i] = _copyMetaData(rawPath[i], rawPath[i].slice(0));
  }
  return _copyMetaData(rawPath, a);
}
export function reverseSegment(segment) {
  let i = 0,
    y;
  segment.reverse();
  for (; i < segment.length; i += 2) {
    y = segment[i];
    segment[i] = segment[i + 1];
    segment[i + 1] = y;
  }
  segment.reversed = !segment.reversed;
}
let _createPath = (e, ignore) => {
    let path = document.createElementNS("http://www.w3.org/2000/svg", "path"),
      attr = [].slice.call(e.attributes),
      i = attr.length,
      name;
    ignore = "," + ignore + ",";
    while (--i > -1) {
      name = attr[i].nodeName.toLowerCase();
      if (ignore.indexOf("," + name + ",") < 0) {
        path.setAttributeNS(null, name, attr[i].nodeValue);
      }
    }
    return path;
  },
  _typeAttrs = {
    rect: "rx,ry,x,y,width,height",
    circle: "r,cx,cy",
    ellipse: "rx,ry,cx,cy",
    line: "x1,x2,y1,y2"
  },
  _attrToObj = (e, attrs) => {
    let props = attrs ? attrs.split(",") : [],
      obj = {},
      i = props.length;
    while (--i > -1) {
      obj[props[i]] = +e.getAttribute(props[i]) || 0;
    }
    return obj;
  };
export function convertToPath(element, swap) {
  let type = element.tagName.toLowerCase(),
    circ = 0.552284749831,
    data,
    x,
    y,
    r,
    ry,
    path,
    rcirc,
    rycirc,
    points,
    w,
    h,
    x2,
    x3,
    x4,
    x5,
    x6,
    y2,
    y3,
    y4,
    y5,
    y6,
    attr;
  if (type === "path" || !element.getBBox) {
    return element;
  }
  path = _createPath(element, "x,y,width,height,cx,cy,rx,ry,r,x1,x2,y1,y2,points");
  attr = _attrToObj(element, _typeAttrs[type]);
  if (type === "rect") {
    r = attr.rx;
    ry = attr.ry || r;
    x = attr.x;
    y = attr.y;
    w = attr.width - r * 2;
    h = attr.height - ry * 2;
    if (r || ry) {
      x2 = x + r * (1 - circ);
      x3 = x + r;
      x4 = x3 + w;
      x5 = x4 + r * circ;
      x6 = x4 + r;
      y2 = y + ry * (1 - circ);
      y3 = y + ry;
      y4 = y3 + h;
      y5 = y4 + ry * circ;
      y6 = y4 + ry;
      data = "M" + x6 + "," + y3 + " V" + y4 + " C" + [x6, y5, x5, y6, x4, y6, x4 - (x4 - x3) / 3, y6, x3 + (x4 - x3) / 3, y6, x3, y6, x2, y6, x, y5, x, y4, x, y4 - (y4 - y3) / 3, x, y3 + (y4 - y3) / 3, x, y3, x, y2, x2, y, x3, y, x3 + (x4 - x3) / 3, y, x4 - (x4 - x3) / 3, y, x4, y, x5, y, x6, y2, x6, y3].join(",") + "z";
    } else {
      data = "M" + (x + w) + "," + y + " v" + h + " h" + -w + " v" + -h + " h" + w + "z";
    }
  } else if (type === "circle" || type === "ellipse") {
    if (type === "circle") {
      r = ry = attr.r;
      rycirc = r * circ;
    } else {
      r = attr.rx;
      ry = attr.ry;
      rycirc = ry * circ;
    }
    x = attr.cx;
    y = attr.cy;
    rcirc = r * circ;
    data = "M" + (x + r) + "," + y + " C" + [x + r, y + rycirc, x + rcirc, y + ry, x, y + ry, x - rcirc, y + ry, x - r, y + rycirc, x - r, y, x - r, y - rycirc, x - rcirc, y - ry, x, y - ry, x + rcirc, y - ry, x + r, y - rycirc, x + r, y].join(",") + "z";
  } else if (type === "line") {
    data = "M" + attr.x1 + "," + attr.y1 + " L" + attr.x2 + "," + attr.y2;
  } else if (type === "polyline" || type === "polygon") {
    points = (element.getAttribute("points") + "").match(_numbersExp) || [];
    x = points.shift();
    y = points.shift();
    data = "M" + x + "," + y + " L" + points.join(",");
    if (type === "polygon") {
      data += "," + x + "," + y + "z";
    }
  }
  path.setAttribute("d", rawPathToString(path._gsRawPath = stringToRawPath(data)));
  if (swap && element.parentNode) {
    element.parentNode.insertBefore(path, element);
    element.parentNode.removeChild(element);
  }
  return path;
}
export function getRotationAtProgress(rawPath, progress) {
  let d = getProgressData(rawPath, progress >= 1 ? 1 - 1e-9 : progress ? progress : 1e-9);
  return getRotationAtBezierT(d.segment, d.i, d.t);
}
function getRotationAtBezierT(segment, i, t) {
  let a = segment[i],
    b = segment[i + 2],
    c = segment[i + 4],
    x;
  a += (b - a) * t;
  b += (c - b) * t;
  a += (b - a) * t;
  x = b + (c + (segment[i + 6] - c) * t - b) * t - a;
  a = segment[i + 1];
  b = segment[i + 3];
  c = segment[i + 5];
  a += (b - a) * t;
  b += (c - b) * t;
  a += (b - a) * t;
  return _round(_atan2(b + (c + (segment[i + 7] - c) * t - b) * t - a, x) * _RAD2DEG);
}
export function sliceRawPath(rawPath, start, end) {
  end = _isUndefined(end) ? 1 : _roundPrecise(end) || 0;
  start = _roundPrecise(start) || 0;
  let loops = Math.max(0, ~~(_abs(end - start) - 1e-8)),
    path = copyRawPath(rawPath);
  if (start > end) {
    start = 1 - start;
    end = 1 - end;
    _reverseRawPath(path);
    path.totalLength = 0;
  }
  if (start < 0 || end < 0) {
    let offset = Math.abs(~~Math.min(start, end)) + 1;
    start += offset;
    end += offset;
  }
  path.totalLength || cacheRawPathMeasurements(path);
  let wrap = end > 1,
    s = getProgressData(path, start, _temp, true),
    e = getProgressData(path, end, _temp2),
    eSeg = e.segment,
    sSeg = s.segment,
    eSegIndex = e.segIndex,
    sSegIndex = s.segIndex,
    ei = e.i,
    si = s.i,
    sameSegment = sSegIndex === eSegIndex,
    sameBezier = ei === si && sameSegment,
    wrapsBehind,
    sShift,
    eShift,
    i,
    copy,
    totalSegments,
    l,
    j;
  if (wrap || loops) {
    wrapsBehind = eSegIndex < sSegIndex || sameSegment && ei < si || sameBezier && e.t < s.t;
    if (_splitSegment(path, sSegIndex, si, s.t)) {
      sSegIndex++;
      if (!wrapsBehind) {
        eSegIndex++;
        if (sameBezier) {
          e.t = (e.t - s.t) / (1 - s.t);
          ei = 0;
        } else if (sameSegment) {
          ei -= si;
        }
      }
    }
    if (Math.abs(1 - (end - start)) < 1e-5) {
      eSegIndex = sSegIndex - 1;
    } else if (!e.t && eSegIndex) {
      eSegIndex--;
    } else if (_splitSegment(path, eSegIndex, ei, e.t) && wrapsBehind) {
      sSegIndex++;
    }
    if (s.t === 1) {
      sSegIndex = (sSegIndex + 1) % path.length;
    }
    copy = [];
    totalSegments = path.length;
    l = 1 + totalSegments * loops;
    j = sSegIndex;
    l += (totalSegments - sSegIndex + eSegIndex) % totalSegments;
    for (i = 0; i < l; i++) {
      _appendOrMerge(copy, path[j++ % totalSegments]);
    }
    path = copy;
  } else {
    eShift = e.t === 1 ? 6 : subdivideSegment(eSeg, ei, e.t);
    if (start !== end) {
      sShift = subdivideSegment(sSeg, si, sameBezier ? s.t / e.t : s.t);
      sameSegment && (eShift += sShift);
      eSeg.splice(ei + eShift + 2);
      (sShift || si) && sSeg.splice(0, si + sShift);
      i = path.length;
      while (i--) {
        (i < sSegIndex || i > eSegIndex) && path.splice(i, 1);
      }
    } else {
      eSeg.angle = getRotationAtBezierT(eSeg, ei + eShift, 0);
      ei += eShift;
      s = eSeg[ei];
      e = eSeg[ei + 1];
      eSeg.length = eSeg.totalLength = 0;
      eSeg.totalPoints = path.totalPoints = 8;
      eSeg.push(s, e, s, e, s, e, s, e);
    }
  }
  path.totalLength = 0;
  return path;
}
function measureSegment(segment, startIndex, bezierQty) {
  startIndex = startIndex || 0;
  if (!segment.samples) {
    segment.samples = [];
    segment.lookup = [];
  }
  let resolution = ~~segment.resolution || 12,
    inc = 1 / resolution,
    endIndex = bezierQty ? startIndex + bezierQty * 6 + 1 : segment.length,
    x1 = segment[startIndex],
    y1 = segment[startIndex + 1],
    samplesIndex = startIndex ? startIndex / 6 * resolution : 0,
    samples = segment.samples,
    lookup = segment.lookup,
    min = (startIndex ? segment.minLength : _largeNum) || _largeNum,
    prevLength = samples[samplesIndex + bezierQty * resolution - 1],
    length = startIndex ? samples[samplesIndex - 1] : 0,
    i,
    j,
    x4,
    x3,
    x2,
    xd,
    xd1,
    y4,
    y3,
    y2,
    yd,
    yd1,
    inv,
    t,
    lengthIndex,
    l,
    segLength;
  samples.length = lookup.length = 0;
  for (j = startIndex + 2; j < endIndex; j += 6) {
    x4 = segment[j + 4] - x1;
    x3 = segment[j + 2] - x1;
    x2 = segment[j] - x1;
    y4 = segment[j + 5] - y1;
    y3 = segment[j + 3] - y1;
    y2 = segment[j + 1] - y1;
    xd = xd1 = yd = yd1 = 0;
    if (_abs(x4) < .01 && _abs(y4) < .01 && _abs(x2) + _abs(y2) < .01) {
      if (segment.length > 8) {
        segment.splice(j, 6);
        j -= 6;
        endIndex -= 6;
      }
    } else {
      for (i = 1; i <= resolution; i++) {
        t = inc * i;
        inv = 1 - t;
        xd = xd1 - (xd1 = (t * t * x4 + 3 * inv * (t * x3 + inv * x2)) * t);
        yd = yd1 - (yd1 = (t * t * y4 + 3 * inv * (t * y3 + inv * y2)) * t);
        l = _sqrt(yd * yd + xd * xd);
        if (l < min) {
          min = l;
        }
        length += l;
        samples[samplesIndex++] = length;
      }
    }
    x1 += x4;
    y1 += y4;
  }
  if (prevLength) {
    prevLength -= length;
    for (; samplesIndex < samples.length; samplesIndex++) {
      samples[samplesIndex] += prevLength;
    }
  }
  if (samples.length && min) {
    segment.totalLength = segLength = samples[samples.length - 1] || 0;
    segment.minLength = min;
    if (segLength / min < 9999) {
      l = lengthIndex = 0;
      for (i = 0; i < segLength; i += min) {
        lookup[l++] = samples[lengthIndex] < i ? ++lengthIndex : lengthIndex;
      }
    }
  } else {
    segment.totalLength = samples[0] = 0;
  }
  return startIndex ? length - samples[startIndex / 2 - 1] : length;
}
export function cacheRawPathMeasurements(rawPath, resolution) {
  let pathLength, points, i;
  for (i = pathLength = points = 0; i < rawPath.length; i++) {
    rawPath[i].resolution = ~~resolution || 12;
    points += rawPath[i].length;
    pathLength += measureSegment(rawPath[i]);
  }
  rawPath.totalPoints = points;
  rawPath.totalLength = pathLength;
  return rawPath;
}
export function subdivideSegment(segment, i, t) {
  if (t <= 0 || t >= 1) {
    return 0;
  }
  let ax = segment[i],
    ay = segment[i + 1],
    cp1x = segment[i + 2],
    cp1y = segment[i + 3],
    cp2x = segment[i + 4],
    cp2y = segment[i + 5],
    bx = segment[i + 6],
    by = segment[i + 7],
    x1a = ax + (cp1x - ax) * t,
    x2 = cp1x + (cp2x - cp1x) * t,
    y1a = ay + (cp1y - ay) * t,
    y2 = cp1y + (cp2y - cp1y) * t,
    x1 = x1a + (x2 - x1a) * t,
    y1 = y1a + (y2 - y1a) * t,
    x2a = cp2x + (bx - cp2x) * t,
    y2a = cp2y + (by - cp2y) * t;
  x2 += (x2a - x2) * t;
  y2 += (y2a - y2) * t;
  segment.splice(i + 2, 4, _round(x1a), _round(y1a), _round(x1), _round(y1), _round(x1 + (x2 - x1) * t), _round(y1 + (y2 - y1) * t), _round(x2), _round(y2), _round(x2a), _round(y2a));
  segment.samples && segment.samples.splice(i / 6 * segment.resolution | 0, 0, 0, 0, 0, 0, 0, 0);
  return 6;
}
function getProgressData(rawPath, progress, decoratee, pushToNextIfAtEnd) {
  decoratee = decoratee || {};
  rawPath.totalLength || cacheRawPathMeasurements(rawPath);
  if (progress < 0 || progress > 1) {
    progress = _wrapProgress(progress);
  }
  let segIndex = 0,
    segment = rawPath[0],
    samples,
    resolution,
    length,
    min,
    max,
    i,
    t;
  if (!progress) {
    t = i = segIndex = 0;
    segment = rawPath[0];
  } else if (progress === 1) {
    t = 1;
    segIndex = rawPath.length - 1;
    segment = rawPath[segIndex];
    i = segment.length - 8;
  } else {
    if (rawPath.length > 1) {
      length = rawPath.totalLength * progress;
      max = i = 0;
      while ((max += rawPath[i++].totalLength) < length) {
        segIndex = i;
      }
      segment = rawPath[segIndex];
      min = max - segment.totalLength;
      progress = (length - min) / (max - min) || 0;
    }
    samples = segment.samples;
    resolution = segment.resolution;
    length = segment.totalLength * progress;
    i = segment.lookup.length ? segment.lookup[~~(length / segment.minLength)] || 0 : _getSampleIndex(samples, length, progress);
    min = i ? samples[i - 1] : 0;
    max = samples[i];
    if (max < length) {
      min = max;
      max = samples[++i];
    }
    t = 1 / resolution * ((length - min) / (max - min) + i % resolution);
    i = ~~(i / resolution) * 6;
    if (pushToNextIfAtEnd && t === 1) {
      if (i + 6 < segment.length) {
        i += 6;
        t = 0;
      } else if (segIndex + 1 < rawPath.length) {
        i = t = 0;
        segment = rawPath[++segIndex];
      }
    }
  }
  decoratee.t = t;
  decoratee.i = i;
  decoratee.path = rawPath;
  decoratee.segment = segment;
  decoratee.segIndex = segIndex;
  return decoratee;
}
export function getPositionOnPath(rawPath, progress, includeAngle, point) {
  let segment = rawPath[0],
    result = point || {},
    samples,
    resolution,
    length,
    min,
    max,
    i,
    t,
    a,
    inv;
  if (progress < 0 || progress > 1) {
    progress = _wrapProgress(progress);
  }
  segment.lookup || cacheRawPathMeasurements(rawPath);
  if (rawPath.length > 1) {
    length = rawPath.totalLength * progress;
    max = i = 0;
    while ((max += rawPath[i++].totalLength) < length) {
      segment = rawPath[i];
    }
    min = max - segment.totalLength;
    progress = (length - min) / (max - min) || 0;
  }
  samples = segment.samples;
  resolution = segment.resolution;
  length = segment.totalLength * progress;
  i = segment.lookup.length ? segment.lookup[progress < 1 ? ~~(length / segment.minLength) : segment.lookup.length - 1] || 0 : _getSampleIndex(samples, length, progress);
  min = i ? samples[i - 1] : 0;
  max = samples[i];
  if (max < length) {
    min = max;
    max = samples[++i];
  }
  t = 1 / resolution * ((length - min) / (max - min) + i % resolution) || 0;
  inv = 1 - t;
  i = ~~(i / resolution) * 6;
  a = segment[i];
  result.x = _round((t * t * (segment[i + 6] - a) + 3 * inv * (t * (segment[i + 4] - a) + inv * (segment[i + 2] - a))) * t + a);
  result.y = _round((t * t * (segment[i + 7] - (a = segment[i + 1])) + 3 * inv * (t * (segment[i + 5] - a) + inv * (segment[i + 3] - a))) * t + a);
  if (includeAngle) {
    result.angle = segment.totalLength ? getRotationAtBezierT(segment, i, t >= 1 ? 1 - 1e-9 : t ? t : 1e-9) : segment.angle || 0;
  }
  return result;
}
export function transformRawPath(rawPath, a, b, c, d, tx, ty) {
  let j = rawPath.length,
    segment,
    l,
    i,
    x,
    y;
  while (--j > -1) {
    segment = rawPath[j];
    l = segment.length;
    for (i = 0; i < l; i += 2) {
      x = segment[i];
      y = segment[i + 1];
      segment[i] = x * a + y * c + tx;
      segment[i + 1] = x * b + y * d + ty;
    }
  }
  rawPath._dirty = 1;
  return rawPath;
}
function arcToSegment(lastX, lastY, rx, ry, angle, largeArcFlag, sweepFlag, x, y) {
  if (lastX === x && lastY === y) {
    return;
  }
  rx = _abs(rx);
  ry = _abs(ry);
  let angleRad = angle % 360 * _DEG2RAD,
    cosAngle = _cos(angleRad),
    sinAngle = _sin(angleRad),
    PI = Math.PI,
    TWOPI = PI * 2,
    dx2 = (lastX - x) / 2,
    dy2 = (lastY - y) / 2,
    x1 = cosAngle * dx2 + sinAngle * dy2,
    y1 = -sinAngle * dx2 + cosAngle * dy2,
    x1_sq = x1 * x1,
    y1_sq = y1 * y1,
    radiiCheck = x1_sq / (rx * rx) + y1_sq / (ry * ry);
  if (radiiCheck > 1) {
    rx = _sqrt(radiiCheck) * rx;
    ry = _sqrt(radiiCheck) * ry;
  }
  let rx_sq = rx * rx,
    ry_sq = ry * ry,
    sq = (rx_sq * ry_sq - rx_sq * y1_sq - ry_sq * x1_sq) / (rx_sq * y1_sq + ry_sq * x1_sq);
  if (sq < 0) {
    sq = 0;
  }
  let coef = (largeArcFlag === sweepFlag ? -1 : 1) * _sqrt(sq),
    cx1 = coef * (rx * y1 / ry),
    cy1 = coef * -(ry * x1 / rx),
    sx2 = (lastX + x) / 2,
    sy2 = (lastY + y) / 2,
    cx = sx2 + (cosAngle * cx1 - sinAngle * cy1),
    cy = sy2 + (sinAngle * cx1 + cosAngle * cy1),
    ux = (x1 - cx1) / rx,
    uy = (y1 - cy1) / ry,
    vx = (-x1 - cx1) / rx,
    vy = (-y1 - cy1) / ry,
    temp = ux * ux + uy * uy,
    angleStart = (uy < 0 ? -1 : 1) * Math.acos(ux / _sqrt(temp)),
    angleExtent = (ux * vy - uy * vx < 0 ? -1 : 1) * Math.acos((ux * vx + uy * vy) / _sqrt(temp * (vx * vx + vy * vy)));
  isNaN(angleExtent) && (angleExtent = PI);
  if (!sweepFlag && angleExtent > 0) {
    angleExtent -= TWOPI;
  } else if (sweepFlag && angleExtent < 0) {
    angleExtent += TWOPI;
  }
  angleStart %= TWOPI;
  angleExtent %= TWOPI;
  let segments = Math.ceil(_abs(angleExtent) / (TWOPI / 4)),
    rawPath = [],
    angleIncrement = angleExtent / segments,
    controlLength = 4 / 3 * _sin(angleIncrement / 2) / (1 + _cos(angleIncrement / 2)),
    ma = cosAngle * rx,
    mb = sinAngle * rx,
    mc = sinAngle * -ry,
    md = cosAngle * ry,
    i;
  for (i = 0; i < segments; i++) {
    angle = angleStart + i * angleIncrement;
    x1 = _cos(angle);
    y1 = _sin(angle);
    ux = _cos(angle += angleIncrement);
    uy = _sin(angle);
    rawPath.push(x1 - controlLength * y1, y1 + controlLength * x1, ux + controlLength * uy, uy - controlLength * ux, ux, uy);
  }
  for (i = 0; i < rawPath.length; i += 2) {
    x1 = rawPath[i];
    y1 = rawPath[i + 1];
    rawPath[i] = x1 * ma + y1 * mc + cx;
    rawPath[i + 1] = x1 * mb + y1 * md + cy;
  }
  rawPath[i - 2] = x;
  rawPath[i - 1] = y;
  return rawPath;
}
export function stringToRawPath(d) {
  let a = (d + "").replace(_scientific, m => {
      let n = +m;
      return n < 0.0001 && n > -0.0001 ? 0 : n;
    }).match(_svgPathExp) || [],
    path = [],
    relativeX = 0,
    relativeY = 0,
    twoThirds = 2 / 3,
    elements = a.length,
    points = 0,
    errorMessage = "ERROR: malformed path: " + d,
    i,
    j,
    x,
    y,
    command,
    isRelative,
    segment,
    startX,
    startY,
    difX,
    difY,
    beziers,
    prevCommand,
    flag1,
    flag2,
    line = function (sx, sy, ex, ey) {
      difX = (ex - sx) / 3;
      difY = (ey - sy) / 3;
      segment.push(sx + difX, sy + difY, ex - difX, ey - difY, ex, ey);
    };
  if (!d || !isNaN(a[0]) || isNaN(a[1])) {
    console.log(errorMessage);
    return path;
  }
  for (i = 0; i < elements; i++) {
    prevCommand = command;
    if (isNaN(a[i])) {
      command = a[i].toUpperCase();
      isRelative = command !== a[i];
    } else {
      i--;
    }
    x = +a[i + 1];
    y = +a[i + 2];
    if (isRelative) {
      x += relativeX;
      y += relativeY;
    }
    if (!i) {
      startX = x;
      startY = y;
    }
    if (command === "M") {
      if (segment) {
        if (segment.length < 8) {
          path.length -= 1;
        } else {
          points += segment.length;
        }
      }
      relativeX = startX = x;
      relativeY = startY = y;
      segment = [x, y];
      path.push(segment);
      i += 2;
      command = "L";
    } else if (command === "C") {
      if (!segment) {
        segment = [0, 0];
      }
      if (!isRelative) {
        relativeX = relativeY = 0;
      }
      segment.push(x, y, relativeX + a[i + 3] * 1, relativeY + a[i + 4] * 1, relativeX += a[i + 5] * 1, relativeY += a[i + 6] * 1);
      i += 6;
    } else if (command === "S") {
      difX = relativeX;
      difY = relativeY;
      if (prevCommand === "C" || prevCommand === "S") {
        difX += relativeX - segment[segment.length - 4];
        difY += relativeY - segment[segment.length - 3];
      }
      if (!isRelative) {
        relativeX = relativeY = 0;
      }
      segment.push(difX, difY, x, y, relativeX += a[i + 3] * 1, relativeY += a[i + 4] * 1);
      i += 4;
    } else if (command === "Q") {
      difX = relativeX + (x - relativeX) * twoThirds;
      difY = relativeY + (y - relativeY) * twoThirds;
      if (!isRelative) {
        relativeX = relativeY = 0;
      }
      relativeX += a[i + 3] * 1;
      relativeY += a[i + 4] * 1;
      segment.push(difX, difY, relativeX + (x - relativeX) * twoThirds, relativeY + (y - relativeY) * twoThirds, relativeX, relativeY);
      i += 4;
    } else if (command === "T") {
      difX = relativeX - segment[segment.length - 4];
      difY = relativeY - segment[segment.length - 3];
      segment.push(relativeX + difX, relativeY + difY, x + (relativeX + difX * 1.5 - x) * twoThirds, y + (relativeY + difY * 1.5 - y) * twoThirds, relativeX = x, relativeY = y);
      i += 2;
    } else if (command === "H") {
      line(relativeX, relativeY, relativeX = x, relativeY);
      i += 1;
    } else if (command === "V") {
      line(relativeX, relativeY, relativeX, relativeY = x + (isRelative ? relativeY - relativeX : 0));
      i += 1;
    } else if (command === "L" || command === "Z") {
      if (command === "Z") {
        x = startX;
        y = startY;
        segment.closed = true;
      }
      if (command === "L" || _abs(relativeX - x) > 0.5 || _abs(relativeY - y) > 0.5) {
        line(relativeX, relativeY, x, y);
        if (command === "L") {
          i += 2;
        }
      }
      relativeX = x;
      relativeY = y;
    } else if (command === "A") {
      flag1 = a[i + 4];
      flag2 = a[i + 5];
      difX = a[i + 6];
      difY = a[i + 7];
      j = 7;
      if (flag1.length > 1) {
        if (flag1.length < 3) {
          difY = difX;
          difX = flag2;
          j--;
        } else {
          difY = flag2;
          difX = flag1.substr(2);
          j -= 2;
        }
        flag2 = flag1.charAt(1);
        flag1 = flag1.charAt(0);
      }
      beziers = arcToSegment(relativeX, relativeY, +a[i + 1], +a[i + 2], +a[i + 3], +flag1, +flag2, (isRelative ? relativeX : 0) + difX * 1, (isRelative ? relativeY : 0) + difY * 1);
      i += j;
      if (beziers) {
        for (j = 0; j < beziers.length; j++) {
          segment.push(beziers[j]);
        }
      }
      relativeX = segment[segment.length - 2];
      relativeY = segment[segment.length - 1];
    } else {
      console.log(errorMessage);
    }
  }
  i = segment.length;
  if (i < 6) {
    path.pop();
    i = 0;
  } else if (segment[0] === segment[i - 2] && segment[1] === segment[i - 1]) {
    segment.closed = true;
  }
  path.totalPoints = points + i;
  return path;
}
export function bezierToPoints(x1, y1, x2, y2, x3, y3, x4, y4, threshold, points, index) {
  let x12 = (x1 + x2) / 2,
    y12 = (y1 + y2) / 2,
    x23 = (x2 + x3) / 2,
    y23 = (y2 + y3) / 2,
    x34 = (x3 + x4) / 2,
    y34 = (y3 + y4) / 2,
    x123 = (x12 + x23) / 2,
    y123 = (y12 + y23) / 2,
    x234 = (x23 + x34) / 2,
    y234 = (y23 + y34) / 2,
    x1234 = (x123 + x234) / 2,
    y1234 = (y123 + y234) / 2,
    dx = x4 - x1,
    dy = y4 - y1,
    d2 = _abs((x2 - x4) * dy - (y2 - y4) * dx),
    d3 = _abs((x3 - x4) * dy - (y3 - y4) * dx),
    length;
  if (!points) {
    points = [x1, y1, x4, y4];
    index = 2;
  }
  points.splice(index || points.length - 2, 0, x1234, y1234);
  if ((d2 + d3) * (d2 + d3) > threshold * (dx * dx + dy * dy)) {
    length = points.length;
    bezierToPoints(x1, y1, x12, y12, x123, y123, x1234, y1234, threshold, points, index);
    bezierToPoints(x1234, y1234, x234, y234, x34, y34, x4, y4, threshold, points, index + 2 + (points.length - length));
  }
  return points;
}
export function flatPointsToSegment(points, curviness = 1) {
  let x = points[0],
    y = 0,
    segment = [x, y],
    i = 2;
  for (; i < points.length; i += 2) {
    segment.push(x, y, points[i], y = (points[i] - x) * curviness / 2, x = points[i], -y);
  }
  return segment;
}
export function pointsToSegment(points, curviness) {
  _abs(points[0] - points[2]) < 1e-4 && _abs(points[1] - points[3]) < 1e-4 && (points = points.slice(2));
  let l = points.length - 2,
    x = +points[0],
    y = +points[1],
    nextX = +points[2],
    nextY = +points[3],
    segment = [x, y, x, y],
    dx2 = nextX - x,
    dy2 = nextY - y,
    closed = Math.abs(points[l] - x) < 0.001 && Math.abs(points[l + 1] - y) < 0.001,
    prevX,
    prevY,
    i,
    dx1,
    dy1,
    r1,
    r2,
    r3,
    tl,
    mx1,
    mx2,
    mxm,
    my1,
    my2,
    mym;
  if (closed) {
    points.push(nextX, nextY);
    nextX = x;
    nextY = y;
    x = points[l - 2];
    y = points[l - 1];
    points.unshift(x, y);
    l += 4;
  }
  curviness = curviness || curviness === 0 ? +curviness : 1;
  for (i = 2; i < l; i += 2) {
    prevX = x;
    prevY = y;
    x = nextX;
    y = nextY;
    nextX = +points[i + 2];
    nextY = +points[i + 3];
    if (x === nextX && y === nextY) {
      continue;
    }
    dx1 = dx2;
    dy1 = dy2;
    dx2 = nextX - x;
    dy2 = nextY - y;
    r1 = _sqrt(dx1 * dx1 + dy1 * dy1);
    r2 = _sqrt(dx2 * dx2 + dy2 * dy2);
    r3 = _sqrt((dx2 / r2 + dx1 / r1) ** 2 + (dy2 / r2 + dy1 / r1) ** 2);
    tl = (r1 + r2) * curviness * 0.25 / r3;
    mx1 = x - (x - prevX) * (r1 ? tl / r1 : 0);
    mx2 = x + (nextX - x) * (r2 ? tl / r2 : 0);
    mxm = x - (mx1 + ((mx2 - mx1) * (r1 * 3 / (r1 + r2) + 0.5) / 4 || 0));
    my1 = y - (y - prevY) * (r1 ? tl / r1 : 0);
    my2 = y + (nextY - y) * (r2 ? tl / r2 : 0);
    mym = y - (my1 + ((my2 - my1) * (r1 * 3 / (r1 + r2) + 0.5) / 4 || 0));
    if (x !== prevX || y !== prevY) {
      segment.push(_round(mx1 + mxm), _round(my1 + mym), _round(x), _round(y), _round(mx2 + mxm), _round(my2 + mym));
    }
  }
  x !== nextX || y !== nextY || segment.length < 4 ? segment.push(_round(nextX), _round(nextY), _round(nextX), _round(nextY)) : segment.length -= 2;
  if (segment.length === 2) {
    segment.push(x, y, x, y, x, y);
  } else if (closed) {
    segment.splice(0, 6);
    segment.length = segment.length - 6;
  }
  return segment;
}
function pointToSegDist(x, y, x1, y1, x2, y2) {
  let dx = x2 - x1,
    dy = y2 - y1,
    t;
  if (dx || dy) {
    t = ((x - x1) * dx + (y - y1) * dy) / (dx * dx + dy * dy);
    if (t > 1) {
      x1 = x2;
      y1 = y2;
    } else if (t > 0) {
      x1 += dx * t;
      y1 += dy * t;
    }
  }
  return (x - x1) ** 2 + (y - y1) ** 2;
}
function simplifyStep(points, first, last, tolerance, simplified) {
  let maxSqDist = tolerance,
    firstX = points[first],
    firstY = points[first + 1],
    lastX = points[last],
    lastY = points[last + 1],
    index,
    i,
    d;
  for (i = first + 2; i < last; i += 2) {
    d = pointToSegDist(points[i], points[i + 1], firstX, firstY, lastX, lastY);
    if (d > maxSqDist) {
      index = i;
      maxSqDist = d;
    }
  }
  if (maxSqDist > tolerance) {
    index - first > 2 && simplifyStep(points, first, index, tolerance, simplified);
    simplified.push(points[index], points[index + 1]);
    last - index > 2 && simplifyStep(points, index, last, tolerance, simplified);
  }
}
export function simplifyPoints(points, tolerance) {
  let prevX = parseFloat(points[0]),
    prevY = parseFloat(points[1]),
    temp = [prevX, prevY],
    l = points.length - 2,
    i,
    x,
    y,
    dx,
    dy,
    result,
    last;
  tolerance = (tolerance || 1) ** 2;
  for (i = 2; i < l; i += 2) {
    x = parseFloat(points[i]);
    y = parseFloat(points[i + 1]);
    dx = prevX - x;
    dy = prevY - y;
    if (dx * dx + dy * dy > tolerance) {
      temp.push(x, y);
      prevX = x;
      prevY = y;
    }
  }
  temp.push(parseFloat(points[l]), parseFloat(points[l + 1]));
  last = temp.length - 2;
  result = [temp[0], temp[1]];
  simplifyStep(temp, 0, last, tolerance, result);
  result.push(temp[last], temp[last + 1]);
  return result;
}
function getClosestProgressOnBezier(iterations, px, py, start, end, slices, x0, y0, x1, y1, x2, y2, x3, y3) {
  let inc = (end - start) / slices,
    best = 0,
    t = start,
    x,
    y,
    d,
    dx,
    dy,
    inv;
  _bestDistance = _largeNum;
  while (t <= end) {
    inv = 1 - t;
    x = inv * inv * inv * x0 + 3 * inv * inv * t * x1 + 3 * inv * t * t * x2 + t * t * t * x3;
    y = inv * inv * inv * y0 + 3 * inv * inv * t * y1 + 3 * inv * t * t * y2 + t * t * t * y3;
    dx = x - px;
    dy = y - py;
    d = dx * dx + dy * dy;
    if (d < _bestDistance) {
      _bestDistance = d;
      best = t;
    }
    t += inc;
  }
  return iterations > 1 ? getClosestProgressOnBezier(iterations - 1, px, py, Math.max(best - inc, 0), Math.min(best + inc, 1), slices, x0, y0, x1, y1, x2, y2, x3, y3) : best;
}
export function getClosestData(rawPath, x, y, slices) {
  let closest = {
      j: 0,
      i: 0,
      t: 0
    },
    bestDistance = _largeNum,
    i,
    j,
    t,
    segment;
  for (j = 0; j < rawPath.length; j++) {
    segment = rawPath[j];
    for (i = 0; i < segment.length; i += 6) {
      t = getClosestProgressOnBezier(1, x, y, 0, 1, slices || 20, segment[i], segment[i + 1], segment[i + 2], segment[i + 3], segment[i + 4], segment[i + 5], segment[i + 6], segment[i + 7]);
      if (bestDistance > _bestDistance) {
        bestDistance = _bestDistance;
        closest.j = j;
        closest.i = i;
        closest.t = t;
      }
    }
  }
  return closest;
}
export function subdivideSegmentNear(x, y, segment, slices, iterations) {
  let l = segment.length,
    bestDistance = _largeNum,
    bestT = 0,
    bestSegmentIndex = 0,
    t,
    i;
  slices = slices || 20;
  iterations = iterations || 3;
  for (i = 0; i < l; i += 6) {
    t = getClosestProgressOnBezier(1, x, y, 0, 1, slices, segment[i], segment[i + 1], segment[i + 2], segment[i + 3], segment[i + 4], segment[i + 5], segment[i + 6], segment[i + 7]);
    if (bestDistance > _bestDistance) {
      bestDistance = _bestDistance;
      bestT = t;
      bestSegmentIndex = i;
    }
  }
  t = getClosestProgressOnBezier(iterations, x, y, bestT - 0.05, bestT + 0.05, slices, segment[bestSegmentIndex], segment[bestSegmentIndex + 1], segment[bestSegmentIndex + 2], segment[bestSegmentIndex + 3], segment[bestSegmentIndex + 4], segment[bestSegmentIndex + 5], segment[bestSegmentIndex + 6], segment[bestSegmentIndex + 7]);
  subdivideSegment(segment, bestSegmentIndex, t);
  return bestSegmentIndex + 6;
}
export function rawPathToString(rawPath) {
  if (_isNumber(rawPath[0])) {
    rawPath = [rawPath];
  }
  let result = "",
    l = rawPath.length,
    sl,
    s,
    i,
    segment;
  for (s = 0; s < l; s++) {
    segment = rawPath[s];
    result += "M" + _round(segment[0]) + "," + _round(segment[1]) + " C";
    sl = segment.length;
    for (i = 2; i < sl; i++) {
      result += _round(segment[i++]) + "," + _round(segment[i++]) + " " + _round(segment[i++]) + "," + _round(segment[i++]) + " " + _round(segment[i++]) + "," + _round(segment[i]) + " ";
    }
    if (segment.closed) {
      result += "z";
    }
  }
  return result;
}