import { IsNotEmpty, IsString, Length } from "class-validator";

export class CreateCompanyDto {

    @IsString()
    @IsNotEmpty()
    name: string

    @IsString()
    @IsNotEmpty()
    website: string

    @IsString()
    @IsNotEmpty()
    @Length(14,14)
    cnpj: string

}