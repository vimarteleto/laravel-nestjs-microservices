import { Controller, Get, Post, Body, Put, Param, Delete } from '@nestjs/common';
import { Types } from 'mongoose';
import { CompaniesService } from './companies.service';
import { CreateCompanyDto } from './dto/create-company.dto';
import { UpdateCompanyDto } from './dto/update-company.dto';

@Controller('companies')
export class CompaniesController {
  constructor(private readonly companiesService: CompaniesService) {}

  @Post()
  create(@Body() createCompanyDto: CreateCompanyDto) {
    return this.companiesService.create(createCompanyDto);
  }

  @Get()
  findAll() {
    return this.companiesService.findAll();
  }

  @Get(':cnpj')
  findOne(@Param('cnpj') cnpj: string) {
    return this.companiesService.findOne(cnpj);
  }

  @Put(':cnpj')
  update(@Param('cnpj') cnpj: string, @Body() updateCompanyDto: UpdateCompanyDto) {
    return this.companiesService.update(cnpj, updateCompanyDto);
  }

  @Delete(':cnpj')
  remove(@Param('cnpj') cnpj: string) {
    return this.companiesService.remove(cnpj);
  }

  @Post('many')
  createMany(@Body() createCompanyDto: CreateCompanyDto[]) {
    return this.companiesService.createMany(createCompanyDto);
  }
}
