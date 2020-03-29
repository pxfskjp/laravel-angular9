import { BaseModel } from "@app/common/model/basemodel";

export class TokenModel extends BaseModel {
    
    private _accessToken: string;
    private _refreshToken: string;
    private _email: string;

    constructor(
        model?: {}
    ) {
        super(model);
    }

    public set accessToken(value: string) {
        this._accessToken = value;
    }

    public get accessToken(): string {
        return this._accessToken;
    }

    public set refreshToken(value: string) {
        this._refreshToken = value;
    }

    public get refreshToken(): string {
        return this._refreshToken;
    }
    
    public set email(value: string) {
        this._email = value;
    }
    
    public get email(): string {
        return this._email;
    }

    fillModel(model: {}): TokenModel {
        this._accessToken = this.setFieldValue(model['accessToken'], '');
        this._refreshToken = this.setFieldValue(model['refreshToken'], '');
        this._email = this.setFieldValue(model['email'], '');
        return this;
    }

    getArray(): {} {
        return {
            accessToken: this._accessToken,
            refreshToken: this._refreshToken,
            email: this._email
        };
    }
}

