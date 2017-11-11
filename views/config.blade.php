<ul class="nav nav-tabs">
    <li role="presentation" class="active"><a data-toggle="tab" href="#main">تنظیمات پایه</a></li>
    <li role="presentation"><a data-toggle="tab" href="#zp">زرین پال</a></li>
    <li role="presentation"><a data-toggle="tab" href="#saman">سامان</a></li>
    <li role="presentation"><a data-toggle="tab" href="#parsian">پارسیان</a></li>
    <li role="presentation"><a data-toggle="tab" href="#pasargad">پاسارگاد</a></li>
    <li role="presentation"><a data-toggle="tab" href="#sadad">سداد</a></li>
    <li role="presentation"><a data-toggle="tab" href="#mellat">ملت</a></li>
</ul>
<div class="tab-content">
    <div id="main" class="tab-pane active in">
        <div class="form-group">
            <label>درگاه فعال</label><br>
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-primary @if(Setting::get('gateway') == 'zarinpal') active @endif">
                    <input type="radio" autocomplete="off" name="key['gateway']" value="zarinpal" @if(Setting::get('gateway') == 'zarinpal') checked @endif> زرین پال
                </label>
                <label class="btn btn-primary @if(Setting::get('gateway') == 'saman') active @endif">
                    <input type="radio" autocomplete="off" name="key['gateway']" value="saman" @if(Setting::get('gateway') == 'saman') checked @endif> سامان
                </label>
                <label class="btn btn-primary @if(Setting::get('gateway') == 'parsian') active @endif">
                    <input type="radio" autocomplete="off" name="key['gateway']" value="parsian" @if(Setting::get('gateway') == 'parsian') checked @endif> پارسیان
                </label>
                <label class="btn btn-primary @if(Setting::get('gateway') == 'pasargad') active @endif">
                    <input type="radio" autocomplete="off" name="key['gateway']" value="pasargad" @if(Setting::get('gateway') == 'pasargad') checked @endif> پاسارگاد
                </label>
                <label class="btn btn-primary @if(Setting::get('gateway') == 'sadad') active @endif">
                    <input type="radio" autocomplete="off" name="key['gateway']" value="sadad" @if(Setting::get('gateway') == 'sadad') checked @endif> سداد
                </label>
                <label class="btn btn-primary @if(Setting::get('gateway') == 'mellat') active @endif">
                    <input type="radio" autocomplete="off" name="key['gateway']" value="mellat" @if(Setting::get('gateway') == 'mellat') checked @endif> ملت
                </label>
            </div>
        </div>
    </div>
    <div id="zp" class="tab-pane fade">
        <div class="form-group">
            <label for="zarinpal-merchant">کد پذیرنده</label>
            <input type="text" id="zarinpal-merchant" name="key['zarinpal-merchant']" class="form-control" value="{{Setting::get('zarinpal-merchant')}}">
        </div>
        <div class="form-group">
            <label for="zarinpal-email">ایمیل</label>
            <input type="text" id="zarinpal-email" name="key['zarinpal-email']" class="form-control" value="{{Setting::get('zarinpal-email')}}">
        </div>
        <div class="form-group">
            <label for="zarinpal-mobile">موبایل</label>
            <input type="text" id="zarinpal-mobile" name="key['zarinpal-mobile']" class="form-control" value="{{Setting::get('zarinpal-mobile')}}">
        </div>
        <div class="form-group">
            <label for="zarinpal-description">توضیحات</label>
            <input type="text" id="zarinpal-description" name="key['zarinpal-description']" class="form-control"value="{{Setting::get('zarinpal-description')}}">
        </div>
        <div class="form-group">
            <label for="zarinpal-cb-url">آدرس بازگشت</label>
            <input type="text" id="zarinpal-cb-url" name="key['zarinpal-cb-url']" class="form-control" value="{{Setting::get('zarinpal-cb-url')}}">
        </div>
        <div class="form-group">
            <label>نوع درگاه</label><br>
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-primary @if(Setting::get('zarinpal-type') == 'zarin-gate') active @endif">
                    <input type="radio" autocomplete="off" name="key['zarinpal-type']" value="zarin-gate" @if(Setting::get('zarinpal-type') == 'zarin-gate') checked @endif> زرین گیت
                </label>
                <label class="btn btn-primary @if(Setting::get('zarinpal-type') == 'normal') active @endif">
                    <input type="radio" autocomplete="off" name="key['zarinpal-type']" value="normal" @if(Setting::get('zarinpal-type') == 'normal') checked @endif> عادی
                </label>
            </div>
        </div>
        <div class="form-group">
            <label>سرور</label><br>
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-primary @if(Setting::get('zarinpal-server', 'test') == 'iran') active @endif">
                    <input type="radio" autocomplete="off" name="key['zarinpal-server']" value="iran" @if(Setting::get('zarinpal-server', 'test') == 'iran') checked @endif> ایران
                </label>
                <label class="btn btn-primary @if(Setting::get('zarinpal-server', 'test') == 'germany') active @endif">
                    <input type="radio" autocomplete="off" name="key['zarinpal-server']" value="germany" @if(Setting::get('zarinpal-server', 'test') == 'germany') checked @endif> آلمان
                </label>
                <label class="btn btn-primary @if(Setting::get('zarinpal-server', 'test') == 'test') active @endif ">
                    <input type="radio" autocomplete="off" name="key['zarinpal-server']" value="test" @if(Setting::get('zarinpal-server', 'test') == 'test') checked @endif> تست
                </label>
            </div>
        </div>
    </div>
    <div id="saman" class="tab-pane fade">
        <div class="form-group">
            <label for="saman-merchant">کد پذیرنده</label>
            <input type="text" id="saman-merchant" name="key['saman-merchant']" class="form-control" value="{{Setting::get('saman-merchant')}}">
        </div>
        <div class="form-group">
            <label for="saman-password">رمز عبور</label>
            <input type="password" id="saman-password" name="key['saman-password']" class="form-control" value="{{Setting::get('saman-password')}}">
        </div>
        <div class="form-group">
            <label for="saman-cb-url">آدرس بازگشت</label>
            <input type="text" id="saman-cb-url" name="key['saman-cb-url']" class="form-control" value="{{Setting::get('saman-cb-url')}}">
        </div>
    </div>
    <div id="parsian" class="tab-pane fade">
        <div class="form-group">
            <label for="parsian-pin">پین</label>
            <input type="text" id="parsian-pin" name="key['parsian-pin']" class="form-control" value="{{Setting::get('parsian-pin')}}">
        </div>
        <div class="form-group">
            <label for="parsian-cb-url">آدرس بازگشت</label>
            <input type="text" id="parsian-cb-url" name="key['parsian-cb-url']" class="form-control" value="{{Setting::get('parsian-cb-url')}}">
        </div>
    </div>
    <div id="pasargad" class="tab-pane fade">
        <div class="form-group">
            <label for="pasargad-merchant">کد پذیرنده</label>
            <input type="text" id="pasargad-merchant" name="key['pasargad-merchant']" class="form-control" value="{{Setting::get('pasargad-merchant')}}">
        </div>
        <div class="form-group">
            <label for="pasargad-terminal">ترمینال</label>
            <input type="text" id="pasargad-terminal" name="key['pasargad-terminal']" class="form-control" value="{{Setting::get('pasargad-terminal')}}">
        </div>
        <div class="form-group">
            <label for="pasargad-cb-url">آدرس بازگشت</label>
            <input type="text" id="pasargad-cb-url" name="key['pasargad-cb-url']" class="form-control" value="{{Setting::get('pasargad-cb-url')}}">
        </div>
    </div>
    <div id="sadad" class="tab-pane fade">
        <div class="form-group">
            <label for="sadad-merchant">کد پذیرنده</label>
            <input type="text" id="sadad-merchant" name="key['sadad-merchant']" class="form-control" value="{{Setting::get('sadad-merchant')}}">
        </div>
        <div class="form-group">
            <label for="sadad-terminal">ترمینال</label>
            <input type="text" id="sadad-terminal" name="key['sadad-terminal']" class="form-control" value="{{Setting::get('sadad-terminal')}}">
        </div>
        <div class="form-group">
            <label for="sadad-transactionKey">کلید تبادل</label>
            <input type="text" id="sadad-transactionKey" name="key['sadad-transactionKey']" class="form-control" value="{{Setting::get('sadad-transactionKey')}}">
        </div>
        <div class="form-group">
            <label for="sadad-cb-url">آدرس بازگشت</label>
            <input type="text" id="sadad-cb-url" name="key['sadad-cb-url']" class="form-control" value="{{Setting::get('sadad-cb-url')}}">
        </div>
    </div>
    <div id="mellat" class="tab-pane fade">
        <div class="form-group">
            <label for="mellat-username">نام کاربری</label>
            <input type="text" id="mellat-username" name="key['mellat-username']" class="form-control" value="{{Setting::get('mellat-username')}}">
        </div>
        <div class="form-group">
            <label for="mellat-password">کلمه عبور</label>
            <input type="password" id="mellat-password" name="key['mellat-password']" class="form-control" value="{{Setting::get('mellat-password')}}">
        </div>
        <div class="form-group">
            <label for="mellat-terminal">ترمینال</label>
            <input type="text" id="mellat-terminal" name="key['mellat-terminal']" class="form-control" value="{{Setting::get('mellat-terminal')}}">
        </div>
        <div class="form-group">
            <label for="mellat-cb-url">آدرس بازگشت</label>
            <input type="text" id="mellat-cb-url" name="key['mellat-cb-url']" class="form-control" value="{{Setting::get('mellat-cb-url')}}">
        </div>
    </div>
</div>